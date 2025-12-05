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

        // --- 1. DEPARTMAN ERİŞİM YETKİSİ (ANA MANTIK) ---
        // Blade dosyasında @can('access-department', 'uretim') şeklinde kullanacağız.
        Gate::define('access-department', function ($user, $departmentSlug) {

            // Admin her yere girebilir
            if ($user->role === 'admin') {
                return true;
            }

            // Global yönetici (departmanı olmayan yönetici) her yeri görebilir
            if ($user->role === 'yönetici' && is_null($user->department_id)) {
                return true;
            }

            // Kullanıcının departman ilişkisi yüklü mü kontrol et, yoksa yüklemeye çalış veya null dön
            if (!$user->relationLoaded('department')) {
                $user->load('department');
            }

            // Eğer kullanıcının departmanı yoksa hiç bir yere giremez (Admin hariç)
            if (!$user->department) {
                return false;
            }

            // SLUG KONTROLÜ (Veritabanındaki slug ile koddan gelen slug eşleşiyor mu?)
            // Örn: Kullanıcının DB slug'ı 'uretim' ise ve $departmentSlug 'uretim' gelirse TRUE döner.
            return $user->department->slug === $departmentSlug;
        });

        // --- 2. DİĞER YARDIMCI YETKİLER ---

        Gate::define('is-global-manager', function ($user) {
            return $user->role === 'admin' || ($user->role === 'yönetici' && is_null($user->department_id));
        });

        Gate::define('access-admin-features', fn(User $user) => $user->role === 'admin');

        // Rezervasyon Yetkisi (AppServiceProvider'dan buraya taşıdık)
        Gate::define('manage_bookings', function ($user) {
            // User modelinde hasPermission fonksiyonu yoksa hata vermesin diye kontrol ekledik
            return method_exists($user, 'hasPermission') ? $user->hasPermission('manage_bookings') : false;
        });

        // Araç Görev Yönetimi
        Gate::define('manage-assignment', function (User $user, VehicleAssignment $assignment) {
            if (in_array($user->role, ['admin', 'yönetici', 'müdür', 'mudur'])) {
                return true;
            }
            if ($user->id === $assignment->user_id) {
                return true;
            }
            // Bireysel Atama
            if ($assignment->responsible_type === User::class && $assignment->responsible_id === $user->id) {
                return true;
            }
            // Takım Ataması
            if ($assignment->responsible_type === Team::class) {
                // teams ilişkisinin User modelinde tanımlı olduğundan emin olun
                return $user->teams()->where('id', $assignment->responsible_id)->exists();
            }
            return false;
        });
    }
}