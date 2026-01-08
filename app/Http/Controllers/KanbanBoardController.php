<?php

namespace App\Http\Controllers;

use App\Models\KanbanBoard;
use App\Models\BusinessUnit; // Veya senin Unit modelin neyse
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KanbanBoardController extends Controller
{
    /**
     * Panoların listelendiği ekran.
     */
    public function index()
    {
        // Panoları, bağlı olduğu fabrika (business unit) ile beraber çekiyoruz.
        $boards = KanbanBoard::with('businessUnit')->get();
        return view('admin.kanban.index', compact('boards'));
    }

    /**
     * Yeni pano oluşturma formu.
     */
    public function create()
    {
        // Formda seçtirmek için fabrikaları gönderiyoruz
        $businessUnits = BusinessUnit::all();

        // Sistemdeki desteklenen modüller (Senin verdiğin bilgiye göre)
        $modules = [
            'maintenance' => 'Bakım Modülü',
            'logistics' => 'Lojistik Modülü',
            'production' => 'Üretim Modülü',
            'admin' => 'İdari İşler',
        ];

        return view('admin.kanban.create', compact('businessUnits', 'modules'));
    }

    /**
     * Panoyu ve Sütunlarını Veritabanına Kaydetme (Transaction)
     */
    public function store(Request $request)
    {
        // 1. Basit Validasyon
        $request->validate([
            'name' => 'required|string|max:255',
            'business_unit_id' => 'required|exists:business_units,id',
            'module_scope' => 'required|string',
            // Sütunlar formdan array olarak gelecek: columns[0][title], columns[0][color] vb.
            'columns' => 'required|array|min:1',
            'columns.*.title' => 'required|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // A. Panoyu Oluştur
                $board = KanbanBoard::create([
                    'name' => $request->name,
                    'business_unit_id' => $request->business_unit_id,
                    'module_scope' => $request->module_scope,
                ]);

                // B. Sütunları Oluştur
                foreach ($request->columns as $index => $columnData) {
                    $board->columns()->create([
                        'title' => $columnData['title'],
                        'slug' => str()->slug($columnData['title']), // Laravel Helper
                        'color_class' => $columnData['color_class'] ?? 'bg-gray-100',
                        'order_index' => $index, // Formdan gelen sırayı koru
                        'is_default' => isset($columnData['is_default']) ? true : false,
                    ]);
                }
            });

            return redirect()->route('kanban-boards.index')
                ->with('success', 'Kanban panosu ve sütunları başarıyla oluşturuldu.');

        } catch (\Exception $e) {
            // Hata olursa logla ve geri dön
            return back()->withErrors(['error' => 'Kayıt sırasında hata oluştu: ' . $e->getMessage()]);
        }
    }

    /**
     * Düzenleme Formu
     */
    public function edit(KanbanBoard $kanbanBoard)
    {
        // Sütunlarıyla beraber çekiyoruz ki formda gösterebilelim
        $kanbanBoard->load('columns');

        $businessUnits = BusinessUnit::all();
        $modules = [
            'maintenance' => 'Bakım Modülü',
            'logistics' => 'Lojistik Modülü',
            'production' => 'Üretim Modülü',
            'admin' => 'İdari İşler',
        ];

        return view('admin.kanban.edit', compact('kanbanBoard', 'businessUnits', 'modules'));
    }

    /**
     * Güncelleme İşlemi (Biraz daha kompleks - Sync mantığı)
     */
    public function update(Request $request, KanbanBoard $kanbanBoard)
    {
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
                        'is_default' => isset($colData['is_default']) ? true : false,
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

    public function destroy(KanbanBoard $kanbanBoard)
    {
        $kanbanBoard->delete();
        return redirect()->route('kanban-boards.index')->with('success', 'Pano silindi.');
    }
}