<?php

namespace App\Policies;

use App\Models\MaintenancePlan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MaintenancePlanPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MaintenancePlan $plan): bool
    {
        // 1. KURAL: Admin her şeyi düzenleyebilir.
        if ($user->isAdmin()) {
            return true;
        }

        // 2. KURAL: Veriyi oluşturan kullanıcı (user_id) her zaman kendi verisini düzenleyebilir.
        if ($user->id === $plan->user_id) {
            return true;
        }

        // 3. KURAL: Yönetici ise VE planın departmanı ile yöneticinin departmanı aynıysa.
        if ($user->isManager()) {
            // Planın departmanını bul (önce plan tablosuna bak, yoksa oluşturan kişiden al)
            $planDepartmentId = $plan->department_id ?? $plan->user->department_id;

            if ($user->department_id === $planDepartmentId) {
                return true;
            }
        }

        // Hiçbiri değilse yetki yok.
        return false;
    }

    // Silme yetkisi güncelleme ile aynı mantıktaysa:
    public function delete(User $user, MaintenancePlan $plan): bool
    {
        if ($plan->status === 'completed') {
            return false;
        }
        return $this->update($user, $plan);
    }
}