<?php

namespace App\Services;

use App\Models\KanbanCard;
use App\Models\KanbanBoard;
use Illuminate\Support\Facades\DB;

class KanbanService
{
    /**
     * Sistemde tanımlı tüm modül kapsamları.
     * Burası "Sistem Yetenekleri" listesidir.
     */
    public const MODULE_SCOPES = [
        'maintenance' => 'Bakım Modülü',
        'logistics' => 'Lojistik Modülü',
        'production' => 'Üretim Modülü',
        'idari' => 'İdari İşler / Hizmet',
    ];

    /**
     * Kullanıcının departmanına bakarak hangi kanban modülünü
     * kullanması gerektiğini veritabanından (dinamik) bulur.
     */
    public function resolveScopeFromUser($user): ?string
    {
        // Kullanıcının ilk departmanındaki 'kanban_scope' değerini oku.
        // Eğer veritabanında bu alanı doldurduysan (Örn: 'logistics'), sistem onu döndürür.
        return $user->departments->first()?->kanban_scope;
    }

    /**
     * Verilen kapsamın sistemde tanımlı olup olmadığını kontrol eder.
     */
    public function isValidScope(?string $scope): bool
    {
        return $scope && array_key_exists($scope, self::MODULE_SCOPES);
    }

    /**
     * Tüm modül listesini döndürür (View'lar için).
     */
    public function getAllModules(): array
    {
        return self::MODULE_SCOPES;
    }

    /**
     * Bir kartı taşır ve eski/yeni sütunlardaki sıralamayı düzeltir.
     */
    public function moveCard($cardId, $targetColumnId, $newIndex)
    {
        return DB::transaction(function () use ($cardId, $targetColumnId, $newIndex) {
            $card = KanbanCard::findOrFail($cardId);
            $originalColumnId = $card->column_id;
            $originalIndex = $card->sort_order;

            if ($originalColumnId == $targetColumnId) {
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
                KanbanCard::where('column_id', $targetColumnId)
                    ->where('sort_order', '>=', $newIndex)
                    ->increment('sort_order');

                $card->column_id = $targetColumnId;
                $card->sort_order = $newIndex;
                $card->save();

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

    /**
     * Kullanıcının o anki fabrikasındaki KENDİ panolarını ve özetlerini getirir.
     */
    public function getDashboardSummary($userId, $businessUnitId)
    {
        $user = \App\Models\User::find($userId);
        $query = \App\Models\KanbanBoard::where('business_unit_id', $businessUnitId);

        // Eğer God Mode olsun dersen bu satırı ekle:
        if (!$user->isAdmin()) {
            $query->where('user_id', $userId);
        }
        return \App\Models\KanbanBoard::where('user_id', $userId) // Sadece benim panolarım
            ->where('business_unit_id', $businessUnitId)      // Sadece bu fabrikadaki
            ->with([
                'columns' => function ($query) {
                    $query->withCount('cards');
                }
            ])
            ->get()
            ->map(function ($board) {
                // Aktif işleri say (Bitti/İptal olmayanlar)
                $activeTaskCount = $board->columns
                    ->filter(function ($col) {
                    return !in_array($col->slug, ['done', 'completed', 'bitti', 'cancelled', 'iptal', 'teslim-edildi']);
                })
                    ->sum('cards_count');

                return (object) [
                    'name' => $board->name,
                    'scope' => $board->module_scope,
                    'column_count' => $board->columns->count(),
                    'total_tasks' => $activeTaskCount,
                    'route' => route('kanban.board', ['board_id' => $board->id]), // Önceki adımda ID bazlı yapmıştık
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
            'admin' => 'fa-calendar-check',
            default => 'fa-clipboard-list',
        };
    }

    private function getColorForScope($scope)
    {
        return match ($scope) {
            'maintenance' => 'danger',
            'logistics' => 'primary',
            'production' => 'warning',
            'admin' => 'success',
            default => 'secondary',
        };
    }
}