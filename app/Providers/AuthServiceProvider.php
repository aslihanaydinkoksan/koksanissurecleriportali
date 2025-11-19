<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Team;
use App\Models\VehicleAssignment;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        /**
         * YENİ MANTIK: Bir departman linkini kimler görebilir?
         * Kullanım: @can('access-department', 'lojistik')
         */
        Gate::define('access-department', function ($user, $departmentSlug) {

            // 1. Kural: Admin her zaman görür.
            if ($user->role === 'admin') {
                return true;
            }

            // 2. Kural: 'yönetici' rolündekiler
            if ($user->role === 'yönetici') {
                // 2a. Eğer yönetici bir birime bağlı DEĞİLSE (global yönetici), her şeyi görür.
                if (is_null($user->department_id)) {
                    return true;
                }

                // 2b. Eğer yönetici bir birime BAĞLIYSA, sadece kendi birimini görür.
                // Not: Bu kod, User modelinizde 'department' isimli bir ilişkiniz olduğunu
                // ve Department modelinde 'slug' isimli bir sütununuz ('lojistik', 'uretim' vb.) olduğunu varsayar.
                if ($user->department && $user->department->slug === $departmentSlug) {
                    return true;
                }
            }

            // 3. Kural: 'kullanıcı' rolündekiler
            if ($user->role === 'kullanıcı') {
                // Sadece kendi birimlerini görebilirler.
                if ($user->department && $user->department->slug === $departmentSlug) {
                    return true;
                }
            }

            // Diğer tüm durumlarda reddet.
            return false;
        });


        /**
         * YENİ GATE: Bu kullanıcı "Global Yönetici" mi?
         * (Admin VEYA birime bağlı olmayan Yönetici)
         * Kullanım: @can('is-global-manager')
         */
        Gate::define('is-global-manager', function ($user) {

            if ($user->role === 'admin') {
                return true;
            }

            if ($user->role === 'yönetici' && is_null($user->department_id)) {
                return true;
            }

            return false;
        });
        Gate::define('access-admin-features', fn(User $user) => $user->role === 'admin');
        //  Araç Görevleri Yönetimi Yetkilendirmesi
        Gate::define('manage-assignment', function (User $user, VehicleAssignment $assignment) {

            // Admin her zaman izinlidir
            if ($user->role === 'admin') {
                return true;
            }
            if ($user->id === $assignment->user_id) {
                return true;
            }
            if ($user->role === 'yönetici') {
                return true;
            }
            // Görevin kendisine atanmış olması (bireysel veya takım üyesi)
            // Bireysel atama kontrolü
            if ($assignment->responsible_type === User::class && $assignment->responsible_id === $user->id) {
                return true;
            }
            // Takım ataması kontrolü (Kullanıcı takımın üyesi mi?)
            if ($assignment->responsible_type === Team::class) {
                // Not: Team modeli user'ları eagerly load etmelidir.
                // Veya user'ın takımlarından biri mi diye kontrol etmelisiniz.
                if ($user->teams()->where('id', $assignment->responsible_id)->exists()) {
                    return true;
                }
            }

            return false;
        });
    }
}
