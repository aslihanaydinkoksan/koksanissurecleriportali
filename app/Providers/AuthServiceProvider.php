<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Team;
use App\Models\VehicleAssignment;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // --- 1. DEPARTMAN ERİŞİM YETKİSİ (ÇOKLU İLİŞKİ UYUMLU) ---
        Gate::define('access-department', function (User $user, $departmentSlug) {

            // Admin her yere girebilir (User modelindeki isAdmin metodunu kullanır)
            if ($user->isAdmin()) {
                return true;
            }

            // Global yönetici (departmanı olmayan yönetici) her yeri görebilir
            if ($user->isManager() && $user->departments()->count() === 0) {
                return true;
            }

            // İlişki yüklü mü kontrol et, hata almamak için çoğul (departments) yükle
            if (!$user->relationLoaded('departments')) {
                $user->load('departments');
            }

            // SLUG KONTROLÜ: Kullanıcının departman listesinde (Collection) bu slug var mı?
            return $user->departments->contains('slug', $departmentSlug);
        });

        // --- 2. YARDIMCI YETKİLER (MODERNE REVİZE EDİLDİ) ---

        Gate::define('is-global-manager', function (User $user) {
            return $user->isAdmin() || ($user->isManager() && $user->departments()->count() === 0);
        });

        Gate::define('access-admin-features', fn(User $user) => $user->isAdmin());

        // Rezervasyon Yetkisi
        Gate::define('manage_bookings', function (User $user) {
            return method_exists($user, 'hasPermissionTo') ? $user->hasPermissionTo('manage_bookings') : $user->isAdmin();
        });

        // Araç Görev Yönetimi
        Gate::define('manage-assignment', function (User $user, VehicleAssignment $assignment) {
            // Admin veya Yönetici ise tam yetki
            if ($user->isAdmin() || $user->isManager()) {
                return true;
            }

            // Kaydı oluşturan kişi ise
            if ($user->id === $assignment->user_id) {
                return true;
            }

            // Bireysel Sorumlu ise
            if ($assignment->responsible_type === User::class && $assignment->responsible_id === $user->id) {
                return true;
            }

            // Takım Sorumlusu ise (User modelindeki teams() ilişkisine bakar)
            if ($assignment->responsible_type === Team::class) {
                return $user->teams()->where('teams.id', $assignment->responsible_id)->exists();
            }

            return false;
        });
    }
}