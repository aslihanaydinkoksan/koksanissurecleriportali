<?php

namespace App\Traits;

use App\Models\KanbanCard;
use App\Models\KanbanColumn;

trait HasKanban
{
    /**
     * Polimorfik İlişki: Bu işin (MaintenancePlan, Shipment vb.) Kanban kartı.
     */
    public function kanbanCard()
    {
        return $this->morphOne(KanbanCard::class, 'model');
    }

    /**
     * Yardımcı Method: İşin şu anki statüsünü (sütun adını) metin olarak döner.
     * Kullanım: $plan->current_status
     */
    public function getCurrentStatusAttribute()
    {
        return $this->kanbanCard?->column?->title ?? 'Tanımsız'; // Null ise 'Tanımsız' yazar
    }

    /**
     * Yardımcı Method: Bu işi belirli bir panonun varsayılan sütununa atar.
     * (Yeni kayıt oluşturulurken kullanılır)
     */
    public function assignToDefaultColumn($boardId)
    {
        $defaultColumn = KanbanColumn::where('board_id', $boardId)
            ->where('is_default', true)
            ->first();

        if ($defaultColumn) {
            $this->kanbanCard()->create([
                'column_id' => $defaultColumn->id,
                'sort_order' => 9999, // En sona ekle
            ]);
        }
    }
}