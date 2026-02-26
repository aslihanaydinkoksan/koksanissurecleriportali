<?php

namespace App\Http\Controllers;

use App\Models\KanbanBoard;
use App\Models\BusinessUnit;
use Illuminate\Http\Request;
use App\Services\KanbanService;
use Illuminate\Support\Facades\DB;

class KanbanBoardController extends Controller
{
    protected $kanbanService;

    public function __construct(KanbanService $kanbanService)
    {
        $this->middleware('auth');
        $this->kanbanService = $kanbanService;
    }

    /**
     * Panoların listelendiği ekran.
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // 1. Sorguyu başlatıyoruz
        $query = KanbanBoard::query();

        // 2. GOD MODE: Eğer kullanıcı Admin ise fabrika filtresini kaldır
        if ($user->isAdmin()) {
            // Eğer HasBusinessUnit trait'i kullanıyorsan global scope'ları devre dışı bırakıyoruz
            $query->withoutGlobalScopes();
        }

        // 3. İlişkileri yükle (Admin panelinde kimin panosu ve hangi fabrika olduğu görünsün)
        $query->with(['businessUnit', 'user']);

        // 4. Yetki Filtrelemesi
        if (!$user->isAdmin()) {
            // Admin değilse SADECE kendi panolarını görsün
            $query->where('user_id', $user->id);
        } elseif ($request->filled('user_id')) {
            // Admin ise ve bir kullanıcı seçilmişse ona göre filtrele
            $query->where('user_id', $request->user_id);
        }

        $boards = $query->get();

        // Filtreleme dropdown'ı için kullanıcı listesi
        $usersWithBoards = $user->isAdmin()
            ? \App\Models\User::whereHas('kanbanBoards')->get()
            : collect();

        return view('admin.kanban.index', compact('boards', 'usersWithBoards'));
    }

    /**
     * Yeni pano oluşturma formu.
     * (Akıllı Yönlendirme Eklendi: Zaten varsa direkt panoya atar)
     */
    public function create(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $activeUnitId = session('active_unit_id') ?? $user->business_unit_id;

        // 1. İSTENEN KAPSAMI AL
        // URL'den (örn: ?scope=logistics) veya kullanıcı departmanından
        $scope = $request->query('scope') ?: $this->kanbanService->resolveScopeFromUser($user);

        // --- EKLENEN AKILLI YÖNLENDİRME BLOĞU (BAŞLANGIÇ) ---
        if ($scope && $activeUnitId) {
            $existingBoard = KanbanBoard::where('user_id', $user->id)
                ->where('business_unit_id', $activeUnitId)
                ->where('module_scope', $scope)
                ->first();

            // Pano zaten varsa, oluşturma formunu gösterme, direkt panoya yönlendir!
            if ($existingBoard) {
                if (!$request->has('force_new')) {
                    return redirect()->route('kanban.board', ['board_id' => $existingBoard->id])
                        ->with('info', 'Bu modülde zaten bir panonuz olduğu için sizi oraya yönlendirdik.');
                }
            }
        }
        // --- EKLENEN AKILLI YÖNLENDİRME BLOĞU (BİTİŞ) ---

        // 2. Dinamik Yetki & Kapsam Kontrolü
        if (!$user->isAdmin()) {
            if (!$scope || !$this->kanbanService->isValidScope($scope)) {
                return redirect()->route('kanban-boards.index')
                    ->with('error', 'Departmanınıza tanımlı bir kapsam bulunamadı. Lütfen yöneticiye danışın.');
            }
        } else {
            if ($scope && !$this->kanbanService->isValidScope($scope)) {
                $scope = null;
            }
        }

        // 3. View Verilerini Hazırla
        $modules = $this->kanbanService->getAllModules();
        $userUnit = $user->businessUnits()->find($activeUnitId) ?? $user->businessUnits()->first();

        $allUsers = $user->isAdmin()
            ? \App\Models\User::with('businessUnits')->orderBy('name')->get()
            : collect();

        if (!$userUnit && $user->isAdmin()) {
            $userUnit = BusinessUnit::first();
        }

        if (!$userUnit) {
            return redirect()->route('home')->with('error', 'Herhangi bir fabrikaya bağlı değilsiniz.');
        }

        return view('admin.kanban.create', compact('userUnit', 'modules', 'scope', 'allUsers'));
    }

    /**
     * Panoyu Kaydetme
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Validasyona user_id ekleyelim
        $request->validate([
            'name' => 'required|string|max:255',
            'module_scope' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
            'business_unit_id' => 'required|exists:business_units,id',
            'columns' => 'required|array|min:1',
        ]);

        // Servis üzerinden modül kontrolü
        if (!$this->kanbanService->isValidScope($request->module_scope)) {
            return back()->withErrors(['error' => 'Geçersiz modül kapsamı seçildi.']);
        }

        try {
            DB::transaction(function () use ($request, $user) {
                // Admin kontrolünde kod tekrarını (DRY) engelliyoruz
                if ($user->isAdmin() && $request->filled('user_id')) {
                    $ownerId = $request->user_id;
                } else {
                    $ownerId = $user->id;
                }

                // Kayıt işlemi
                $board = KanbanBoard::create([
                    'name' => $request->name,
                    'user_id' => (int) $ownerId,
                    'business_unit_id' => $request->business_unit_id,
                    'module_scope' => $request->module_scope,
                ]);

                foreach ($request->columns as $index => $columnData) {
                    $board->columns()->create([
                        'title' => $columnData['title'],
                        'slug' => str()->slug($columnData['title']),
                        'color_class' => $columnData['color_class'] ?? 'bg-gray-100',
                        'order_index' => $index,
                        'is_default' => (isset($columnData['is_default']) && $columnData['is_default'] == '1'),
                    ]);
                }
                $this->kanbanService->syncExistingData($board);
            });

            return redirect()->route('kanban-boards.index')->with('success', 'Kişisel panonuz başarıyla oluşturuldu.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Kayıt sırasında hata oluştu: ' . $e->getMessage()]);
        }
    }

    /**
     * Düzenleme Formu
     */
    public function edit(KanbanBoard $kanbanBoard)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Yetki Kontrolü: Panonun sahibi mi yoksa Admin mi?
        if ($kanbanBoard->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Bu panoyu yönetme yetkiniz yok.');
        }

        $kanbanBoard->load('columns');
        $modules = $this->kanbanService->getAllModules();

        // Kullanıcının sadece yetkili olduğu fabrikaları göster
        $businessUnits = $user->businessUnits;

        return view('admin.kanban.edit', compact('kanbanBoard', 'businessUnits', 'modules'));
    }

    /**
     * Güncelleme İşlemi
     */
    public function update(Request $request, KanbanBoard $kanbanBoard)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Daha tutarlı olması için edit'teki isAdmin kontrolünü update'de de aynen kullandım.
        // Veya "hasRole('admin')" var ise onu tercih etmeye devam edebilirsiniz, ancak isAdmin var olarak kabul ediliyor.
        if ($kanbanBoard->user_id !== $user->id && !$user->isAdmin() && !$user->hasRole('admin')) {
            abort(403, 'Bu panoyu yönetme yetkiniz yok.');
        }

        // 1. Validasyon
        $request->validate([
            'name' => 'required|string|max:255',
            'columns' => 'required|array|min:1',
            'columns.*.title' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request, $kanbanBoard) {
                // A. Pano Adını Güncelle
                $kanbanBoard->update([
                    'name' => $request->name,
                ]);

                // B. Sütun Senkronizasyonu
                $incomingIds = collect($request->columns)->pluck('id')->filter()->toArray();
                $existingIds = $kanbanBoard->columns()->pluck('id')->toArray();
                $idsToDelete = array_diff($existingIds, $incomingIds);

                // Silme İşlemi
                if (!empty($idsToDelete)) {
                    \App\Models\KanbanColumn::destroy($idsToDelete);
                }

                // Ekleme ve Güncelleme Döngüsü
                foreach ($request->columns as $index => $colData) {
                    $columnId = $colData['id'] ?? null;

                    $dataToSave = [
                        'board_id' => $kanbanBoard->id,
                        'title' => $colData['title'],
                        'slug' => str()->slug($colData['title']),
                        'color_class' => $colData['color_class'] ?? 'bg-light',
                        'order_index' => $index,
                        'is_default' => (isset($colData['is_default']) && $colData['is_default'] == '1'),
                    ];

                    if ($columnId && in_array($columnId, $existingIds)) {
                        // GÜNCELLEME
                        $kanbanBoard->columns()->where('id', $columnId)->update(
                            collect($dataToSave)->except(['board_id'])->toArray()
                        );
                    } else {
                        // EKLEME
                        $kanbanBoard->columns()->create($dataToSave);
                    }
                }
            });

            return redirect()->route('kanban-boards.index')
                ->with('success', 'Pano ve sütun yapısı başarıyla güncellendi.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Güncelleme hatası: ' . $e->getMessage()]);
        }
    }

    /**
     * Merkezi Yönlendirici
     */
    public function show($id)
    {
        dd("TUZAK 1 - Controller: KanbanBoardController, Metod: show", "Gelen ID: " . $id);
        return redirect()->route('kanban.board', ['board_id' => $id]);
    }

    public function destroy(KanbanBoard $kanbanBoard)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($kanbanBoard->user_id !== $user->id && !$user->isAdmin() && !$user->hasRole('admin')) {
            abort(403, 'Bu panoyu yönetme yetkiniz yok.');
        }

        $kanbanBoard->delete();
        return redirect()->route('kanban-boards.index')->with('success', 'Pano silindi.');
    }

    /**
     * AKILLI YÖNLENDİRME (Smart Redirect)
     */
    public function checkAndRedirect(Request $request)
    {
        $scope = $request->query('scope');

        /** @var \App\Models\User $user */
        $user = auth()->user();

        dd("TUZAK 2 - Controller: KanbanBoardController, Metod: checkAndRedirect", "İstenen Scope: " . $scope, "User ID: " . $user->id);

        $activeUnitId = session('active_unit_id') ?? $user->business_unit_id;

        if (!$activeUnitId || !$scope) {
            return redirect()->route('kanban-boards.index')
                ->with('error', 'Yönlendirme için kapsam veya fabrika bilgisi eksik.');
        }

        $existingBoard = KanbanBoard::where('user_id', $user->id)
            ->where('business_unit_id', $activeUnitId)
            ->where('module_scope', $scope)
            ->first();

        if ($existingBoard) {
            return redirect()->route('kanban.board', ['board_id' => $existingBoard->id]);
        }

        return redirect()->route('kanban-boards.create', ['scope' => $scope]);
    }
}
