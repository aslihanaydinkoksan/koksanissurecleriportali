<?php

namespace App\Observers;

use App\Models\User;
use Spatie\Permission\Models\Role;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    /**
     * Kullanıcı OLUŞTURULDUĞUNDA (Created)
     */
    public function created(User $user): void
    {
        $this->assignRoleByDepartment($user);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    /**
     * Kullanıcı GÜNCELLENDİĞİNDE (Updated)
     */
    public function updated(User $user): void
    {
        // Eğer departman ID'si veya 'role' sütunu (admin vs) değiştiyse yetkileri tekrar ayarla
        if ($user->isDirty('department_id') || $user->isDirty('role')) {
            $this->assignRoleByDepartment($user);
        }
    }
    /**
     * Ana Mantık: Departman ID -> Spatie Rol Eşleşmesi
     */
    private function assignRoleByDepartment(User $user): void
    {
        // 1. Admin ise dokunma
        if ($user->role === 'admin' || $user->hasRole('admin')) {
            $user->syncRoles(['admin']);
            return;
        }

        // 2. KRİTİK KORUMA: Eğer kullanıcı "Yönetici" ise, onu "Personel" yapma!
        // Bu sayede "İdari İşler Yöneticisi" olarak kalır.
        if ($user->role === 'yönetici' || $user->hasRole(['yönetici', 'yonetici', 'manager'])) {
            // İstersen burada yönetici rolünü garantiye alabilirsin:
            // $user->syncRoles(['yönetici']); 
            return; // İşlemi burada kes, aşağıya inip personel rolü atamasın.
        }

        // 3. Standart Personel Ataması (Aynen kalsın)
        $roleToAssign = match ($user->department_id) {
            1 => 'lojistik_personeli',
            2 => 'uretim_personeli',
            3 => 'idari_isler_personeli',
            4 => 'bakim_personeli',
            default => 'user',
        };

        if (Role::where('name', $roleToAssign)->exists()) {
            $user->syncRoles([$roleToAssign]);
        }
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the User "restored" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
