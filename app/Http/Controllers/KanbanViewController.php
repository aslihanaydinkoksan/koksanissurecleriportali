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

    public function index(Request $request, $board_id = null)
    {
        $user = auth()->user();

        // 1. GÜVENLİK VE BULMA:
        // Eğer board_id rotadan gelmediyse (null ise), create sayfasına at.
        if (!$board_id) {
            // Opsiyonel: scope varsa ona göre create'e yönlendir
            return redirect()->route('kanban-boards.create');
        }

        // 2. Panoyu bul (Admin ise hepsi, değilse sadece kendisininki)
        $boardQuery = KanbanBoard::with([
            'columns' => fn($q) => $q->orderBy('order_index'),
            'columns.cards' => fn($q) => $q->orderBy('sort_order'),
            'columns.cards.model'
        ]);

        if (!$user->isAdmin()) {
            $boardQuery->where('user_id', $user->id);
        }

        // ID'ye göre panoyu çek
        $board = $boardQuery->where('id', $board_id)->first();

        // 3. Eğer pano bulunamazsa (Silinmişse veya yetkisi yoksa)
        if (!$board) {
            return redirect()->route('kanban-boards.index')
                ->with('error', 'Pano bulunamadı veya erişim yetkiniz yok.');
        }
        if ($board->columns) {
            foreach ($board->columns as $column) {
                // Eğer sütunda kartlar varsa filtrele
                if ($column->cards) {
                    $filteredCards = $column->cards->filter(function ($card) {
                        // Model (asıl iş kaydı) veritabanında duruyor mu?
                        return $card->model !== null;
                    });

                    // Temizlenmiş listeyi sütuna geri yükle
                    $column->setRelation('cards', $filteredCards);
                }
            }
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