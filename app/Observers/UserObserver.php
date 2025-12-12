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
        // 1. Eğer kullanıcı veritabanında 'admin' olarak işaretlenmişse
        // Departmanına bakmaksızın ona 'admin' yetkisi ver.
        if ($user->role === 'admin') {
            $user->syncRoles(['admin']);
            return;
        }

        // 2. Departman ID'sine göre hangi rolü alacak?
        // (Veritabanındaki ID'ler ile Seeder'daki isimleri eşleştiriyoruz)
        $roleToAssign = match ($user->department_id) {
            1 => 'lojistik_personeli',      // ID 1: Lojistik
            2 => 'uretim_personeli',        // ID 2: Üretim
            3 => 'idari_isler_personeli',   // ID 3: İdari İşler
            4 => 'bakim_personeli',         // ID 4: Bakım
            default => 'user',              // Departmanı yoksa standart user
        };

        // 3. Rolü ata (syncRoles mevcut yetkileri siler, yenisini yazar - temiz iş yapar)
        // Eğer özel bir yönetici rolü varsa (booking_manager gibi) onu ezmemek için kontrol eklenebilir
        // Ama standart personel için bu en temizidir.
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
