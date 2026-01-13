<?php

namespace App\Observers;

use App\Models\KanbanBoard;
use App\Models\KanbanColumn;
use Illuminate\Database\Eloquent\Model;

class KanbanModelObserver
{
    /**
     * Yeni bir iş (Bakım, Sevkiyat vb.) oluştuğunda tüm kullanıcıların panosuna ekle.
     */
    public function created(Model $model)
    {
        $factoryId = $model->business_unit_id;
        $scope = $this->determineScope($model);

        if (!$factoryId || !$scope)
            return;

        // Bu birimdeki TÜM kullanıcıların panolarını çekiyoruz
        $boards = KanbanBoard::where('business_unit_id', $factoryId)
            ->where('module_scope', $scope)
            ->get();

        foreach ($boards as $board) {
            // HATA ÇÖZÜMÜ: Property ->columns yerine ->columns() metodunu kullanıyoruz.
            // Böylece bir Query Builder döner ve where/first metodları kesin çalışır.
            $defaultColumn = $board->columns()->where('is_default', true)->first()
                ?? $board->columns()->orderBy('order_index')->first();

            if ($defaultColumn) {
                // Kartı polimorfik olarak bağla
                $model->kanbanCard()->create([
                    'column_id' => $defaultColumn->id,
                    'sort_order' => 9999, // Sütun sonuna ekle
                ]);
            }
        }
    }

    /**
     * İş güncellendiğinde (Statü değiştiğinde) tüm kullanıcıların kartlarını kaydır.
     */
    public function updated(Model $model)
    {
        // Modelin statü sütununu belirle
        $statusColumn = match (get_class($model)) {
            'App\Models\MaintenancePlan' => 'status',
            'App\Models\Shipment' => 'shipment_status',
            'App\Models\Event' => 'visit_status',
            default => null,
        };

        // Eğer statü değişmediyse işlem yapma
        if (!$statusColumn || !$model->isDirty($statusColumn))
            return;

        $targetSlug = $this->mapStatusToSlug(get_class($model), $model->{$statusColumn});
        if (!$targetSlug)
            return;

        // Bu modele bağlı TÜM kartları bul (Tüm kullanıcıların panolarındakiler)
        // Not: Bu ilişki için modellerine morphMany eklemiş olmalısın.
        $cards = $model->kanbanCard;

        if ($cards) {
            foreach ($cards as $card) {
                // Kartın bulunduğu panoda hedef statüye uygun sütunu bul
                $targetColumn = KanbanColumn::where('board_id', $card->column->board_id)
                    ->where('slug', $targetSlug)
                    ->first();

                if ($targetColumn) {
                    $card->update(['column_id' => $targetColumn->id]);
                }
            }
        }
    }

    private function determineScope(Model $model)
    {
        return match (get_class($model)) {
            'App\Models\MaintenancePlan' => 'maintenance',
            'App\Models\Shipment' => 'logistics',
            'App\Models\ProductionPlan' => 'production',
            'App\Models\Event' => 'admin',
            default => null,
        };
    }

    private function mapStatusToSlug($modelClass, $status)
    {
        if ($modelClass === 'App\Models\MaintenancePlan') {
            return match ($status) {
                'open', 'pending', 'new' => 'bekleyenler',
                'in_progress', 'working' => 'islemde',
                'completed', 'done' => 'tamamlandi',
                default => null,
            };
        }
        // Diğer modüller için mapping buraya eklenebilir...
        return null;
    }

    public function deleted(Model $model)
    {
        // İş silindiğinde bağlı tüm kartları da sil
        $model->kanbanCard()->delete();
    }
}