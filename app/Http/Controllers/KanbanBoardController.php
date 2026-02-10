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
        $user = auth()->user();

        // 1. Sorguyu başlatıyoruz
        $query = \App\Models\KanbanBoard::query();

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
        $user = auth()->user();
        $activeUnitId = session('active_unit_id') ?? $user->business_unit_id;

        // 1. İSTENEN KAPSAMI AL
        // URL'den (örn: ?scope=logistics) veya kullanıcı departmanından
        $scope = $request->query('scope') ?: $this->kanbanService->resolveScopeFromUser($user);

        // --- EKLENEN AKILLI YÖNLENDİRME BLOĞU (BAŞLANGIÇ) ---
        // Eğer kullanıcı belirli bir modül için "Oluştur" sayfasına geldiyse,
        // önce o modülde zaten bir panosu var mı diye bakıyoruz.
        if ($scope && $activeUnitId) {
            $existingBoard = \App\Models\KanbanBoard::where('user_id', $user->id)
                ->where('business_unit_id', $activeUnitId)
                ->where('module_scope', $scope)
                ->first();

            // Pano zaten varsa, oluşturma formunu gösterme, direkt panoya yönlendir!
            if ($existingBoard) {
                // Eğer URL'de 'force_create' yoksa yönlendir
                if (!$request->has('force_new')) {
                    return redirect()->route('kanban.board', ['board_id' => $existingBoard->id])
                        ->with('info', 'Bu modülde zaten bir panonuz olduğu için sizi oraya yönlendirdik.');
                }
            }
        }
        // --- EKLENEN AKILLI YÖNLENDİRME BLOĞU (BİTİŞ) ---


        // 2. Dinamik Yetki & Kapsam Kontrolü (Mevcut Kodların)
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

        // 3. View Verilerini Hazırla (Mevcut Kodların)
        $modules = $this->kanbanService->getAllModules();
        $userUnit = $user->businessUnits()->find($activeUnitId) ?? $user->businessUnits()->first();

        $allUsers = $user->isAdmin()
            ? \App\Models\User::with('businessUnits')->orderBy('name')->get()
            : collect();

        if (!$userUnit && $user->isAdmin()) {
            $userUnit = \App\Models\BusinessUnit::first();
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
        $user = auth()->user();

        // Validasyona user_id ekleyelim (Sadece admin için opsiyonel)
        $request->validate([
            'name' => 'required|string|max:255',
            'module_scope' => 'required|string',
            'user_id' => 'nullable|exists:users,id', // Admin başka kullanıcı seçebilir
            'business_unit_id' => 'required|exists:business_units,id',
            'columns' => 'required|array|min:1',
        ]);

        // Servis üzerinden modül kontrolü
        if (!$this->kanbanService->isValidScope($request->module_scope)) {
            return back()->withErrors(['error' => 'Geçersiz modül kapsamı seçildi.']);
        }

        try {
            DB::transaction(function () use ($request, $user) {
                $adminUser = auth()->user();

                if ($adminUser->isAdmin() && $request->filled('user_id')) {
                    $ownerId = $request->user_id;
                } else {
                    $ownerId = $adminUser->id;
                }

                // Kayıt işlemi
                $board = \App\Models\KanbanBoard::create([
                    'name' => $request->name,
                    'user_id' => (int) $ownerId, // Integer'a zorlayarak hata riskini azaltıyoruz
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
        // Yetki Kontrolü: Panonun sahibi mi yoksa Admin mi?
        if ($kanbanBoard->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Bu panoyu yönetme yetkiniz yok.');
        }

        $kanbanBoard->load('columns');
        $modules = $this->kanbanService->getAllModules();

        // Kullanıcının sadece yetkili olduğu fabrikaları göster
        $businessUnits = auth()->user()->businessUnits;

        return view('admin.kanban.edit', compact('kanbanBoard', 'businessUnits', 'modules'));
    }

    /**
     * Güncelleme İşlemi (Biraz daha kompleks - Sync mantığı)
     */
    public function update(Request $request, KanbanBoard $kanbanBoard)
    {
        if ($kanbanBoard->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
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
                // Formdan gelen sütun ID'lerini topla (Yeni eklenenlerin ID'si null/boş olacaktır)
                $incomingIds = collect($request->columns)->pluck('id')->filter()->toArray();

                // Veritabanındaki mevcut ID'leri al
                $existingIds = $kanbanBoard->columns()->pluck('id')->toArray();

                // Silinecekleri Bul: Veritabanında olan ama Formda olmayanlar
                $idsToDelete = array_diff($existingIds, $incomingIds);

                // Silme İşlemi
                if (!empty($idsToDelete)) {
                    // İsteğe bağlı güvenlik: Eğer içinde kart varsa hata fırlatılabilir.
                    // Şimdilik direkt siliyoruz.
                    \App\Models\KanbanColumn::destroy($idsToDelete);
                }

                // Ekleme ve Güncelleme Döngüsü
                foreach ($request->columns as $index => $colData) {
                    $columnId = $colData['id'] ?? null;

                    // Veri seti
                    $dataToSave = [
                        'board_id' => $kanbanBoard->id, // create için gerekli
                        'title' => $colData['title'],
                        'slug' => str()->slug($colData['title']),
                        'color_class' => $colData['color_class'] ?? 'bg-light',
                        'order_index' => $index, // Form sırasına göre indexle
                        'is_default' => (isset($colData['is_default']) && $colData['is_default'] == '1'),
                    ];

                    if ($columnId && in_array($columnId, $existingIds)) {
                        // GÜNCELLEME (Mevcut ID varsa)
                        $kanbanBoard->columns()->where('id', $columnId)->update(
                            collect($dataToSave)->except(['board_id'])->toArray()
                        );
                    } else {
                        // EKLEME (ID yoksa veya geçersizse)
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
     * Merkezi Yönlendirici: Hatalı linkleri asıl Kanban sayfasına bağlar.
     */
    public function show($id)
    {
        dd("TUZAK 1 - Controller: KanbanBoardController, Metod: show", "Gelen ID: " . $id);
        // Hiçbir kontrol yapmadan direkt asıl sayfaya fırlatıyoruz.
        return redirect()->route('kanban.board', ['board_id' => $id]);
    }

    public function destroy(KanbanBoard $kanbanBoard)
    {
        if ($kanbanBoard->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            abort(403, 'Bu panoyu yönetme yetkiniz yok.');
        }
        $kanbanBoard->delete();
        return redirect()->route('kanban-boards.index')->with('success', 'Pano silindi.');
    }
    /**
     * AKILLI YÖNLENDİRME (Smart Redirect)
     * Belirtilen kapsamda (scope) kullanıcının panosu varsa oraya,
     * yoksa oluşturma sayfasına yönlendirir.
     */
    public function checkAndRedirect(Request $request)
    {
        $scope = $request->query('scope');
        $user = auth()->user();
        dd("TUZAK 2 - Controller: KanbanBoardController, Metod: checkAndRedirect", "İstenen Scope: " . $scope, "User ID: " . $user->id);
        // Session'daki aktif fabrikayı veya kullanıcının birimini al
        $activeUnitId = session('active_unit_id') ?? $user->business_unit_id;

        if (!$activeUnitId || !$scope) {
            return redirect()->route('kanban-boards.index')
                ->with('error', 'Yönlendirme için kapsam veya fabrika bilgisi eksik.');
        }

        // Kullanıcının bu fabrikada ve bu kapsamda panosu var mı?
        // NOT: Eğer "Admin herkesin panosunu görsün" dersen sorguyu değiştirebiliriz,
        // ama "Kendi Panosu" mantığı için user_id kontrolü şart.
        $existingBoard = \App\Models\KanbanBoard::where('user_id', $user->id)
            ->where('business_unit_id', $activeUnitId)
            ->where('module_scope', $scope)
            ->first();

        if ($existingBoard) {
            // Pano varsa detayına git
            return redirect()->route('kanban.board', ['board_id' => $existingBoard->id]);
        }

        // Pano yoksa oluşturma sayfasına git (Scope parametresiyle)
        return redirect()->route('kanban-boards.create', ['scope' => $scope]);
    }
}