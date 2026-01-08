<?php

namespace App\Observers;

use App\Models\KanbanBoard;
use Illuminate\Database\Eloquent\Model;

class KanbanModelObserver
{
    /**
     * Handle the Model "created" event.
     */
    public function created(Model $model)
    {
        // 1. Modelin hangi fabrika/birime ait olduğunu bul
        // Senin standardın: $model->business_unit_id
        $factoryId = $model->business_unit_id;

        if (!$factoryId)
            return; // Fabrika yoksa işlem yapma

        // 2. Bu modelin türüne göre (Bakım, Lojistik) hangi panoya gideceğini bul
        // Model sınıf adından 'scope' çıkarımı yapıyoruz
        $scope = $this->determineScope($model);

        if (!$scope)
            return; // Tanımsız bir modelse çık

        // 3. İlgili fabrikanın ve modülün panosunu bul
        $board = KanbanBoard::where('business_unit_id', $factoryId)
            ->where('module_scope', $scope)
            ->first();

        // 4. Panonun "Varsayılan" (is_default=1) sütununu veya ilk sütununu bul
        if ($board) {
            $defaultColumn = $board->columns()->where('is_default', true)->first()
                ?? $board->columns()->orderBy('order_index')->first();

            if ($defaultColumn) {
                // 5. Kartı Oluştur
                $model->kanbanCard()->create([
                    'column_id' => $defaultColumn->id,
                    'sort_order' => 9999, // En sona ekle
                ]);
            }
        }
    }

    /**
     * Hangi Modelin Hangi Modül (Scope) Olduğunu Belirle
     */
    private function determineScope(Model $model)
    {
        $class = get_class($model);

        return match ($class) {
            'App\Models\MaintenancePlan' => 'maintenance',
            'App\Models\Shipment' => 'logistics',
            'App\Models\ProductionPlan' => 'production',
            'App\Models\Event' => 'admin', // İdari İşler
            default => null,
        };
    }
    /**
     * Kayıt Güncellendiğinde Çalışır (Statü Eşitlemesi)
     */
    public function updated(Model $model)
    {
        // 1. Bu kaydın bir Kanban kartı var mı?
        $card = $model->kanbanCard;
        if (!$card)
            return;

        // 2. Statü değişikliği var mı kontrol et
        // Her modülün statü sütunu farklı olabilir, onları tanımlayalım:
        $statusColumn = match (get_class($model)) {
            'App\Models\MaintenancePlan' => 'status', // Bakım tablosundaki sütun adı
            'App\Models\Shipment' => 'shipment_status', // Lojistik (tahmini)
            'App\Models\VehicleAssignment' => 'status', // Ulaştırma
            'App\Models\Event' => 'visit_status', // İdari İşler
            default => null,
        };

        if (!$statusColumn || !$model->isDirty($statusColumn))
            return;

        // 3. Yeni statüye karşılık gelen Kanban Sütun Slug'ını bul
        // BURASI ÖNEMLİ: Veritabanındaki statü kodlarınla, Kanban sütun slug'larını eşleştiriyoruz.
        $newStatus = $model->{$statusColumn};
        $targetSlug = $this->mapStatusToSlug(get_class($model), $newStatus);

        if (!$targetSlug)
            return;

        // 4. Hedef sütunu bul (Aynı panoda olması şart)
        $targetColumn = \App\Models\KanbanColumn::where('board_id', $card->column->board_id)
            ->where('slug', $targetSlug)
            ->first();

        // 5. Kartı taşı
        if ($targetColumn && $targetColumn->id !== $card->column_id) {
            $card->update([
                'column_id' => $targetColumn->id,
                // Sort order'ı güncellemiyoruz, olduğu sırada kalsın veya en başa/sona atabilirsin.
            ]);
        }
    }

    /**
     * Statü Kodlarını Kanban Sluglarına Çeviren Sözlük
     */
    private function mapStatusToSlug($modelClass, $status)
    {
        // Bakım Modülü Eşleştirmesi
        if ($modelClass === 'App\Models\MaintenancePlan') {
            return match ($status) {
                'open', 'pending', 'new' => 'bekleyenler',
                'in_progress', 'working' => 'islemde',
                'completed', 'done' => 'bitti',
                default => null,
            };
        }

        // Lojistik Modülü Eşleştirmesi (Örnek)
        if ($modelClass === 'App\Models\Shipment') {
            return match ($status) {
                'pending' => 'siparis-alindi',
                'on_road', 'transit' => 'yolda',
                'delivered' => 'teslim-edildi',
                default => null,
            };
        }

        return null;
    }
    public function deleted(Model $model)
    {
        $model->kanbanCard()->delete();
    }
}