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
        // 1. Admin veya "Yönetici" rolündekiler HER ŞEYİ onaylayabilir (Departman bağımsız).
        // Bu sayede Yönetici, başka departmanın işine de müdahale edebilir.
        if (in_array($user->role, ['admin', 'yonetici'])) {
            return true;
        }

        // 2. "Müdür" ise SADECE Kendi departmanını onaylayabilir.
        // (Eğer "Yönetici" ile "Müdür"ü ayırıyorsanız bu blok işe yarar)
        if ($user->role === 'mudur') {
            $planDepartmentId = $plan->department_id ?? $plan->user->department_id;
            if ($user->department_id === $planDepartmentId) {
                return true;
            }
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

        // A) Admin ve Yönetici HER ZAMAN düzenler (Departman fark etmeksizin)
        if (in_array($user->role, ['admin', 'yonetici'])) {
            return true;
        }

        // B) Planın SAHİBİ (Oluşturan) her zaman düzenler
        if ($user->id === $plan->user_id) {
            return true;
        }

        // C) Müdürler kendi departmanındaki işleri düzenleyebilir
        if ($user->role === 'mudur') {
            $planDepartmentId = $plan->department_id ?? $plan->user->department_id;
            if ($user->department_id === $planDepartmentId) {
                return true;
            }
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
        // 1. Admin ve Yönetici, tamamlanmış olsa bile silebilir (Temizlik yetkisi).
        if (in_array($user->role, ['admin', 'yonetici'])) {
            return true;
        }

        // 2. Diğer kullanıcılar (Personel/Müdür) TAMAMLANMIŞ işi asla silemez.
        if ($plan->status === 'completed') {
            return false;
        }

        // 3. Diğer durumlarda güncelleme yetkisi olan silebilir mi?
        // Genelde silme yetkisi daha kısıtlıdır, sadece "Sahibi" veya "Yönetici" silmeli.

        // Sahibi ise silebilir
        if ($user->id === $plan->user_id) {
            return true;
        }

        // Müdür ise kendi departmanındakini silebilir
        if ($user->role === 'mudur') {
            $planDepartmentId = $plan->department_id ?? $plan->user->department_id;
            if ($user->department_id === $planDepartmentId) {
                return true;
            }
        }

        return false;
    }
}