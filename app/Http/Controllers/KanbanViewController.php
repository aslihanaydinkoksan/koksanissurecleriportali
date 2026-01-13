<?php

namespace App\Http\Controllers;

use App\Models\KanbanBoard;
use App\Models\KanbanCard;
use App\Services\KanbanService;
use Illuminate\Http\Request;

class KanbanViewController extends Controller
{
    protected $kanbanService;

    public function __construct(KanbanService $kanbanService)
    {
        $this->kanbanService = $kanbanService;
    }

    /**
     * Kullanıcının seçtiği modüle (scope) göre KENDİ panosunu getirir.
     */

    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. URL'den board_id veya scope parametrelerini al
        $boardId = $request->query('board_id');
        $scope = $request->query('scope');

        // 2. Panoyu bul (Sadece bu kullanıcıya ait olanlar arasında)
        $boardQuery = KanbanBoard::with([
            'columns' => fn($q) => $q->orderBy('order_index'),
            'columns.cards' => fn($q) => $q->orderBy('sort_order'),
            'columns.cards.model'
        ])->where('user_id', $user->id);

        if (!$user->isAdmin()) {
            $boardQuery->where('user_id', $user->id);
        }
        if ($boardId) {
            // Eğer belirli bir pano ID'si istenmişse onu getir
            $board = $boardQuery->where('id', $boardId)->first();
        } else {
            // ID yoksa, scope'a göre ilk/varsayılan panoyu getir (Eski linkler bozulmasın diye)
            $board = $boardQuery->where('module_scope', $scope ?: 'maintenance')->first();
        }

        // 3. Eğer pano bulunamazsa oluşturma ekranına yönlendir
        if (!$board) {
            $finalScope = $scope ?: ($this->kanbanService->resolveScopeFromUser($user) ?: 'maintenance');
            return redirect()->route('kanban-boards.create', ['scope' => $finalScope])
                ->with('info', 'Bu bölüm için henüz bir panonuz yok. Hemen oluşturun.');
        }

        // View içinde kullanmak üzere asıl scope'u board üzerinden alalım
        $scope = $board->module_scope;

        return view('admin.kanban.board', compact('board', 'scope'));
    }

    /**
     * Kart taşıma işlemi (AJAX) - Güvenlik Kontrollü
     */
    public function moveCard(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'card_id' => 'required|integer',
            'target_column_id' => 'required|integer',
            'new_index' => 'required|integer'
        ]);

        try {
            // GÜVENLİK: Taşınmaya çalışılan kart gerçekten bu kullanıcıya mı ait?
            $card = KanbanCard::whereHas('column.board', function ($q) use ($user) {
                if (!$user->isAdmin()) {
                    $q->where('user_id', $user->id);
                }
            })->findOrFail($request->card_id);

            $this->kanbanService->moveCard(
                $card->id,
                $request->target_column_id,
                $request->new_index
            );

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Yetkisiz işlem veya hata: ' . $e->getMessage()], 403);
        }
    }

    /**
     * Karta tıklandığında detayları getiren metod (AJAX) - Birim İzolasyonlu
     */
    public function show(KanbanCard $kanbanCard)
    {
        // 1. Polimorfik ilişkiyi yükle
        $kanbanCard->load('model');

        // 2. Veri Bütünlüğü Kontrolü
        if (!$kanbanCard->model) {
            return response()->json(['html' => '<div class="alert alert-danger">Bu iş kaydı silinmiş veya bulunamadı.</div>'], 404);
        }

        // 3. GÜVENLİK KONTROLÜ (Birim İzolasyonu)
        // Kart kullanıcının panosunda olsa bile, asıl veri (Model) kullanıcının fabrikasına ait olmalı.
        $user = auth()->user();
        if (!$user->hasRole('admin') && $user->business_unit_id != $kanbanCard->model->business_unit_id) {
            return response()->json([
                'html' => '<div class="alert alert-danger">Yetkisiz Erişim: Bu veri sizin biriminize ait değil.</div>'
            ], 403);
        }

        return view('admin.kanban.partials.card-detail', compact('kanbanCard'))->render();
    }
}