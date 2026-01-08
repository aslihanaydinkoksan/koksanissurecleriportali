<?php

namespace App\Http\Controllers;

use App\Models\KanbanBoard;
use App\Services\KanbanService;
use Illuminate\Http\Request;

class KanbanViewController extends Controller
{
    protected $kanbanService;

    public function __construct(KanbanService $kanbanService)
    {
        $this->kanbanService = $kanbanService;
    }

    public function index(Request $request)
    {
        // 1. Parametre Kontrolü
        $scope = $request->query('scope', 'maintenance'); // Varsayılan: Bakım

        // 2. Kullanıcının Fabrikasını Bul (Senin auth yapına göre değişebilir)
        // Şimdilik auth()->user()->business_unit_id olduğunu varsayıyorum.
        $userFactoryId = auth()->user()->business_unit_id ?? 1; // Test için 1

        // 3. İlgili Panoyu Çek (Eager Loading ile Sütunlar ve Kartlar)
        $board = KanbanBoard::with(['columns.cards.model']) // Nested Eager Loading
            ->where('business_unit_id', $userFactoryId)
            ->where('module_scope', $scope)
            ->first();

        if (!$board) {
            return redirect()->back()->with('error', 'Bu modül için henüz bir pano tanımlanmamış.');
        }

        return view('admin.kanban.board', compact('board', 'scope'));
    }

    public function moveCard(Request $request)
    {
        $request->validate([
            'card_id' => 'required|integer',
            'target_column_id' => 'required|integer',
            'new_index' => 'required|integer' // 0'dan başlar
        ]);

        try {
            $this->kanbanService->moveCard(
                $request->card_id,
                $request->target_column_id,
                $request->new_index
            );

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    /**
     * Karta tıklandığında detayları getiren metod (AJAX)
     */
    /**
     * Karta tıklandığında detayları getiren metod (AJAX)
     */
    public function show(\App\Models\KanbanCard $kanbanCard)
    {
        // 1. İlişkiyi yükle
        $kanbanCard->load('model');

        // 2. Veri Bütünlüğü Kontrolü: Kart var ama asıl iş (Bakım/Sevkiyat) silinmiş mi?
        if (!$kanbanCard->model) {
            return response()->json(['html' => '<div class="alert alert-danger">Bu iş kaydı silinmiş veya bulunamadı.</div>'], 404);
        }

        // 3. GÜVENLİK KONTROLÜ (Fabrika/Birim İzolasyonu)
        $user = auth()->user();

        // Eğer kullanıcı bir "Süper Admin" değilse ve fabrikası eşleşmiyorsa engelle
        // Not: Eğer adminlerin her şeyi görmesi gerekiyorsa buraya (! $user->hasRole('admin')) ekleyebilirsin.
        if (!$user->hasRole('admin') && $user->business_unit_id != $kanbanCard->model->business_unit_id) {

            // Loglama (Opsiyonel): Yetkisiz giriş denemesini kaydedebilirsin
            \Log::warning("Yetkisiz Erişim Denemesi: User {$user->id}, Card {$kanbanCard->id} kartına erişmeye çalıştı.");

            return response()->json([
                'html' => '<div class="alert alert-danger">
                             <i class="fa fa-exclamation-triangle"></i>
                             Yetkisiz Erişim: Bu kart sizin yetki alanınızdaki bir birime ait değil.
                           </div>'
            ], 403);
        }

        // 4. Her şey yolundaysa detayları render et
        return view('admin.kanban.partials.card-detail', compact('kanbanCard'))->render();
    }
}