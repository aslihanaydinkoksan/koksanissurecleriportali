<?php

namespace App\Services;

use App\Models\KanbanCard;
use App\Models\KanbanBoard;
use App\Models\MaintenancePlan;
use App\Models\Shipment;
use App\Models\ProductionPlan;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KanbanService
{
    /**
     * Sistemde tanımlı tüm modül kapsamları.
     */
    public const MODULE_SCOPES = [
        'maintenance' => 'Bakım Modülü',
        'logistics' => 'Lojistik Modülü',
        'production' => 'Üretim Modülü',
        'idari' => 'İdari İşler / Hizmet',
    ];

    /**
     * Panonun kapsamına göre ilgili Model sınıfını döndürür.
     */
    public function getModelByScope(string $scope): ?string
    {
        return match ($scope) {
            'maintenance' => MaintenancePlan::class,
            'logistics' => Shipment::class,
            'production' => ProductionPlan::class,
            'idari', 'admin' => Event::class,
            default => null,
        };
    }

    /**
     * Modelin statü alanının adını döndürür.
     */
    public function getStatusColumnByScope(string $scope): string
    {
        return match ($scope) {
            'logistics' => 'shipment_status',
            'idari', 'admin' => 'visit_status',
            default => 'status',
        };
    }

    /**
     * Statü değerini Kanban kolon slug'ına çevirir (Mapping).
     * Observer'daki mantıkla uyumlu olmalı.
     */
    public function mapStatusToSlug(string $scope, string $status): string
    {
        $slug = Str::slug($status);

        // Bakım Modülü
        if ($scope === 'maintenance') {
            return match ($status) {
                'open', 'pending', 'new' => 'bekleyenler',
                'in_progress', 'working' => 'islemde',
                'completed', 'done', 'finished' => 'tamamlandi',
                default => $slug,
            };
        }

        // Lojistik Modülü
        if ($scope === 'logistics') {
            return match ($status) {
                'pending' => 'beklemede',
                'on_road' => 'yolda',
                'delivered' => 'teslim-edildi',
                default => $slug,
            };
        }

        // İdari İşler
        if (in_array($scope, ['idari', 'admin'])) {
            return match ($status) {
                'pending' => 'planlandi',
                'active' => 'devam-ediyor',
                'completed' => 'tamamlandi',
                'cancelled' => 'iptal',
                default => $slug,
            };
        }

        return $slug;
    }

    /**
     * KRİTİK METOD: Pano oluşturulduğunda eski verileri tarayıp kart oluşturur.
     * Senin "model_type" ve "model_id" yapına göre uyarlandı.
     */
    public function syncExistingData(KanbanBoard $board): void
    {
        $modelClass = $this->getModelByScope($board->module_scope);

        // Geçersiz scope ise işlem yapma
        if (!$modelClass)
            return;

        // 1. İlgili fabrikanın verilerini çek
        $existingRecords = $modelClass::where('business_unit_id', $board->business_unit_id)->get();

        // 2. Panodaki kolonları al
        $columns = $board->columns;
        if ($columns->isEmpty())
            return;

        $defaultColumn = $columns->where('is_default', true)->first() ?? $columns->first();

        // Bu panoya ait tüm sütun ID'lerini bir diziye al
        $boardColumnIds = $columns->pluck('id')->toArray();

        foreach ($existingRecords as $record) {
            // DÜZELTME: 'record_type' yerine senin tablondaki 'model_type' kullanılıyor.
            $exists = KanbanCard::whereIn('column_id', $boardColumnIds)
                ->where('model_type', $modelClass)
                ->where('model_id', $record->id)
                ->exists();

            if ($exists)
                continue;

            // 3. Hedef kolonu belirle
            $statusColumn = $this->getStatusColumnByScope($board->module_scope);
            $currentStatus = $record->{$statusColumn} ?? 'pending';

            $targetSlug = $this->mapStatusToSlug($board->module_scope, $currentStatus);

            // Kolonu bulmaya çalış, bulamazsan varsayılana at
            $targetColumn = $columns->firstWhere('slug', $targetSlug) ?? $defaultColumn;

            if ($targetColumn) {
                // DÜZELTME: Senin modelindeki 'kanbanCard()' metodunu (tekil) kullanıyoruz.
                // 'model' parametresiyle tanımladığın için Laravel otomatik olarak
                // model_type ve model_id sütunlarını dolduracaktır.
                $record->kanbanCard()->create([
                    'column_id' => $targetColumn->id,
                    'sort_order' => 9999,
                ]);
            }
        }
    }

    // --- Diğer Yardımcı Metodlar (Değişiklik yok) ---

    public function resolveScopeFromUser($user): ?string
    {
        return $user->departments->first()?->kanban_scope;
    }

    public function isValidScope(?string $scope): bool
    {
        return $scope && array_key_exists($scope, self::MODULE_SCOPES);
    }

    public function getAllModules(): array
    {
        return self::MODULE_SCOPES;
    }

    /**
     * Kart taşıma işlemi (Sıralama ve Kolon Güncelleme)
     */
    public function moveCard($cardId, $targetColumnId, $newIndex)
    {
        return DB::transaction(function () use ($cardId, $targetColumnId, $newIndex) {
            $card = KanbanCard::findOrFail($cardId);
            $originalColumnId = $card->column_id;
            $originalIndex = $card->sort_order;

            if ($originalColumnId == $targetColumnId) {
                // Aynı sütun içinde sıralama değişimi
                if ($originalIndex == $newIndex)
                    return true;

                if ($originalIndex < $newIndex) {
                    KanbanCard::where('column_id', $originalColumnId)
                        ->whereBetween('sort_order', [$originalIndex + 1, $newIndex])
                        ->decrement('sort_order');
                } else {
                    KanbanCard::where('column_id', $originalColumnId)
                        ->whereBetween('sort_order', [$newIndex, $originalIndex - 1])
                        ->increment('sort_order');
                }
                $card->sort_order = $newIndex;
                $card->save();
            } else {
                // Farklı sütuna taşıma
                KanbanCard::where('column_id', $targetColumnId)
                    ->where('sort_order', '>=', $newIndex)
                    ->increment('sort_order');

                $card->column_id = $targetColumnId;
                $card->sort_order = $newIndex;
                $card->save();

                // Eski sütunu yeniden sırala (boşlukları kapat)
                $this->reorderColumn($originalColumnId);
            }
            return true;
        });
    }

    private function reorderColumn($columnId)
    {
        $cards = KanbanCard::where('column_id', $columnId)
            ->orderBy('sort_order', 'asc')
            ->get();

        foreach ($cards as $index => $c) {
            if ($c->sort_order !== $index) {
                $c->update(['sort_order' => $index]);
            }
        }
    }

    public function getDashboardSummary($userId, $businessUnitId)
    {
        $user = \App\Models\User::find($userId);
        $query = \App\Models\KanbanBoard::where('business_unit_id', $businessUnitId);

        if (!$user->isAdmin()) {
            $query->where('user_id', $userId);
        }

        $boards = $query->with([
            'columns' => function ($query) {
                $query->withCount('cards'); // Relation adın cards ise
            }
        ])->get();

        return $boards->map(function ($board) {
            $activeTaskCount = $board->columns
                ->filter(function ($col) {
                    return !in_array($col->slug, ['done', 'completed', 'bitti', 'cancelled', 'iptal', 'teslim-edildi', 'tamamlandi']);
                })
                ->sum('cards_count');

            return (object) [
                'name' => $board->name,
                'scope' => $board->module_scope,
                'column_count' => $board->columns->count(),
                'total_tasks' => $activeTaskCount,
                'route' => route('kanban.board', ['board_id' => $board->id]), // Route parametresi board model binding ise 'board' olmalı
                'icon' => $this->getIconForScope($board->module_scope),
                'color' => $this->getColorForScope($board->module_scope)
            ];
        });
    }

    private function getIconForScope($scope)
    {
        return match ($scope) {
            'maintenance' => 'fa-tools',
            'logistics' => 'fa-truck',
            'production' => 'fa-industry',
            'idari', 'admin' => 'fa-calendar-check',
            default => 'fa-clipboard-list',
        };
    }

    private function getColorForScope($scope)
    {
        return match ($scope) {
            'maintenance' => 'danger',
            'logistics' => 'primary',
            'production' => 'warning',
            'idari', 'admin' => 'success',
            default => 'secondary',
        };
    }
}