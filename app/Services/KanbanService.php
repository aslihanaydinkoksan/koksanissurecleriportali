<?php

namespace App\Services;

use App\Models\KanbanCard;
use Illuminate\Support\Facades\DB;

class KanbanService
{
    /**
     * Bir kartı taşır ve eski/yeni sütunlardaki sıralamayı düzeltir.
     */
    public function moveCard($cardId, $targetColumnId, $newIndex)
    {
        return DB::transaction(function () use ($cardId, $targetColumnId, $newIndex) {
            $card = KanbanCard::findOrFail($cardId);

            $originalColumnId = $card->column_id;
            $originalIndex = $card->sort_order;

            // -- AYNI SÜTUN İÇİNDE HAREKET --
            if ($originalColumnId == $targetColumnId) {
                if ($originalIndex == $newIndex)
                    return true; // Değişiklik yok

                // Aradaki kartları kaydır
                if ($originalIndex < $newIndex) {
                    // Aşağı taşıma: Aradakileri yukarı (-1) çek
                    KanbanCard::where('column_id', $originalColumnId)
                        ->whereBetween('sort_order', [$originalIndex + 1, $newIndex])
                        ->decrement('sort_order');
                } else {
                    // Yukarı taşıma: Aradakileri aşağı (+1) it
                    KanbanCard::where('column_id', $originalColumnId)
                        ->whereBetween('sort_order', [$newIndex, $originalIndex - 1])
                        ->increment('sort_order');
                }

                $card->sort_order = $newIndex;
                $card->save();
            }
            // -- FARKLI SÜTUNA HAREKET --
            else {
                // 1. Hedef sütunda yer aç (Gireceği yerden sonrakileri it)
                KanbanCard::where('column_id', $targetColumnId)
                    ->where('sort_order', '>=', $newIndex)
                    ->increment('sort_order');

                // 2. Kartı taşı
                $card->column_id = $targetColumnId;
                $card->sort_order = $newIndex;
                $card->save();

                // 3. ESKİ SÜTUNDAKİ BOŞLUĞU KAPAT (Re-indexing)
                $this->reorderColumn($originalColumnId);
            }

            return true;
        });
    }

    /**
     * Bir sütundaki tüm kartları 0'dan başlayarak yeniden sıralar (Gap Closing)
     */
    private function reorderColumn($columnId)
    {
        $cards = KanbanCard::where('column_id', $columnId)
            ->orderBy('sort_order', 'asc')
            ->get();

        foreach ($cards as $index => $c) {
            // Sadece sırası yanlış olanları güncelle (Performans)
            if ($c->sort_order !== $index) {
                $c->update(['sort_order' => $index]);
            }
        }
    }
    /**
     * Belirli bir fabrikanın tüm panolarını ve özet istatistiklerini getirir.
     * Dashboard/Welcome ekranı için optimize edilmiştir.
     */
    public function getDashboardSummary($businessUnitId)
    {
        return \App\Models\KanbanBoard::where('business_unit_id', $businessUnitId)
            ->with([
                'columns' => function ($query) {
                    // Her sütundaki kart sayısını veritabanı seviyesinde say (Performans)
                    $query->withCount('cards');
                }
            ])
            ->get()
            ->map(function ($board) {
                $activeTaskCount = $board->columns
                    ->filter(function ($col) {
                        return !in_array($col->slug, ['done', 'completed', 'bitti', 'cancelled', 'iptal', 'teslim-edildi', 'siparis-iptal-edildi']);
                    })
                    ->sum('cards_count');
                return (object) [
                    'name' => $board->name,
                    'scope' => $board->module_scope,
                    'column_count' => $board->columns->count(),
                    'total_tasks' => $activeTaskCount,// Toplam iş yükü
                    'route' => route('kanban.board', ['scope' => $board->module_scope]),
                    'icon' => $this->getIconForScope($board->module_scope), // Görsel ikon
                    'color' => $this->getColorForScope($board->module_scope) // Görsel renk
                ];
            });
    }

    // Yardımcı (Private) Metotlar - UI Mantığını serviste tutuyoruz
    private function getIconForScope($scope)
    {
        return match ($scope) {
            'maintenance' => 'fa-tools',
            'logistics' => 'fa-truck',
            'production' => 'fa-industry',
            'admin' => 'fa-calendar-check',
            default => 'fa-clipboard-list',
        };
    }

    private function getColorForScope($scope)
    {
        return match ($scope) {
            'maintenance' => 'danger',   // Kırmızı
            'logistics' => 'primary',    // Mavi
            'production' => 'warning',   // Sarı
            'admin' => 'success',        // Yeşil
            default => 'secondary',
        };
    }
}