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
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MaintenancePlan $plan): bool
    {
        // 1. KURAL: Plan TAMAMLANDIYSA (completed) sadece yetkili (Admin/Yönetici) düzenleyebilir.
        // Standart personel tamamlanmış işi değiştiremez.
        if ($plan->status === 'completed') {
            // Eğer kullanıcının onay yetkisi varsa (Yönetici/Müdür) değiştirebilir.
            if ($user->can('approve', $plan)) {
                return true;
            }
            return false;
        }

        // 2. KURAL: Plan ONAY BEKLİYORSA (pending_approval), standart personel değiştiremez.
        // Sadece reddetmek/onaylamak isteyen yönetici değiştirebilir.
        if ($plan->status === 'pending_approval') {
            if ($user->can('approve', $plan)) {
                return true;
            }
            // Personel onaydaki işi geri çekemez (Controller'da da ek kontrolümüz var ama burada da keselim)
            return false;
        }

        // 3. KURAL: Diğer durumlarda (Açık, İşlemde, İptal)

        // Admin her zaman düzenler
        if ($user->isAdmin()) {
            return true;
        }

        // Planın SAHİBİ (Oluşturan) her zaman düzenler
        if ($user->id === $plan->user_id) {
            return true;
        }

        // Yöneticiler kendi departmanlarındaki işleri düzenler
        $planDepartmentId = $plan->department_id ?? $plan->user->department_id;
        if ($user->isManagerOrDirector()) {
            if ($user->department_id === $planDepartmentId) {
                return true;
            }
        }
        // C) AYNI DEPARTMAN İZNİ:
        // Eğer giriş yapan kullanıcı ile planı oluşturan kişi AYNI DEPARTMANDAYSA izin ver.
        // Böylece Yönetici iş atadığında, Bakım Personeli o işi yapıp tamamlayabilir.
        if ($user->department_id && $user->department_id == $planDepartmentId) {
            return true;
        }

        // D) Planın SAHİBİ (Oluşturan) her zaman düzenler (Departman değişse bile)
        if ($user->id === $plan->user_id) {
            return true;
        }

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
    /**
     * Kullanıcı bu planı "Tamamlandı" durumuna getirebilir mi?
     */
    public function approve(User $user, MaintenancePlan $plan): bool
    {
        // 1. Admin her zaman onaylayabilir.
        if ($user->isAdmin()) {
            return true;
        }

        // 2. Yönetici veya Müdür ise VE Kendi departmanıysa onaylayabilir.
        if ($user->isManagerOrDirector()) {
            // Planın departmanını bul (ilişki veya kullanıcı üzerinden)
            $planDepartmentId = $plan->department_id ?? $plan->user->department_id;

            if ($user->department_id === $planDepartmentId) {
                return true;
            }
        }

        // Diğerleri (Standart kullanıcılar) onaylayamaz.
        return false;
    }
}