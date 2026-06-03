<?php

namespace App\Policies;

use App\Models\MaintenancePlan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MaintenancePlanPolicy
{
    /**
     * Kullanıcı bu planı "Tamamlandı" durumuna getirebilir mi (veya onaylayabilir mi)?
     * Bu metod diğer metodlar tarafından da (update/delete) referans alınır.
     */
    public function approve(User $user, MaintenancePlan $plan): bool
    {
        // 1. Admin veya Yönetici yetkisi olanlar (Bakım Müdürü dahil) HER ŞEYİ onaylayabilir.
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        return false;
    }

    /**
     * Kullanıcı kaydı düzenleyebilir mi?
     */
    public function update(User $user, MaintenancePlan $plan): bool
    {
        // --- 1. ÖZEL DURUMLAR (Tamamlanmış veya Onay Bekleyen) ---

        // Tamamlanmış (completed) veya Onay Bekleyen (pending_approval) işleri
        // sadece onay yetkisi olanlar (Admin/Yönetici/İlgili Müdür) değiştirebilir.
        if (in_array($plan->status, ['completed', 'pending_approval'])) {
            return $this->approve($user, $plan);
        }

        // --- 2. GENEL DÜZENLEME YETKİLERİ ---

        // A) Admin ve Yönetici HER ZAMAN düzenler
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        // B) Planın SAHİBİ (Oluşturan) her zaman düzenler
        if ($user->id === $plan->user_id) {
            return true;
        }

        // D) AYNI DEPARTMAN PERSONELİ İZNİ (Opsiyonel):
        // Bakım ekibi bir havuz mantığıyla çalışıyorsa, aynı departmandaki arkadaşının işini düzenleyebilir.
        // İstemiyorsanız bu bloğu kaldırabilirsiniz.
        $planDepartmentId = $plan->department_id ?? $plan->user->department_id;
        if ($user->department_id && $user->department_id == $planDepartmentId) {
            return true;
        }

        return false;
    }

    /**
     * Kullanıcı kaydı silebilir mi?
     */
    public function delete(User $user, MaintenancePlan $plan): bool
    {
        // 1. Admin ve Yönetici, tamamlanmış olsa bile silebilir.
        if ($user->isAdmin() || $user->isManager()) {
            return true;
        }

        // 2. Diğer kullanıcılar TAMAMLANMIŞ işi asla silemez.
        if ($plan->status === 'completed') {
            return false;
        }

        // 3. Sahibi ise silebilir
        if ($user->id === $plan->user_id) {
            return true;
        }

        return false;
    }
}