<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate; // YENİ: Gate'i import edin
use App\Models\User;
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
    public function boot()
    {
        $this->registerPolicies();

        // --- YENİ EKLENECEK KOD BAŞLANGICI ---

        /**
         * Bir kullanıcının belirli bir birime ait (veya o birim üzerinde işlem yapma yetkisine sahip)
         * olup olmadığını kontrol eden genel bir Gate.
         *
         * Kullanımı: @can('access-department', 'lojistik')
         * $this->authorize('access-department', 'uretim');
         */
        Gate::define('access-department', function (User $user, string $departmentSlug) {

            // 1. Kural: 'admin' veya 'yönetici' rolündeki kullanıcılar HER ZAMAN yetkilidir.
            // Not: Sizin 'Hızlı Eylemler' mantığınızdan yola çıkarak 'yönetici' rolünü de
            // admin gibi tüm birimlere erişebilir olarak tanımlıyorum. 
            // Eğer 'yönetici' sadece kendi birimini yönetmeliyse, 'admin' ile yer değiştiririz.
            if ($user->role === 'admin' || $user->role === 'yönetici') {
                return true;
            }

            // 2. Kural: Kullanıcının birimi atanmış mı ve bu birimin 'slug'ı
            // istenen 'slug' ile eşleşiyor mu?
            // (department?->slug, $user->department null olsa bile hata vermemek içindir)
            return $user->department?->slug === $departmentSlug;
        });

        // --- YENİ EKLENECEK KOD BİTİŞİ ---

        Gate::define('manage-assignment', function (User $user, VehicleAssignment $assignment) {
            // Eğer kullanıcı 'admin' ise VEYA 
            // kullanıcının ID'si, bu görevi oluşturan kullanıcının ID'sine eşitse:
            // --- HATA AYIKLAMA GÜNLÜKLERİ ---
            Log::info('--- GATE DEBUG: manage-assignment ---');
            Log::info('Giriş Yapan Kullanıcı ID: ' . $user->id . ' (Adı: ' . $user->name . ')');
            Log::info('Görevin user_id Sütunu: ' . $assignment->user_id);
            Log::info('Kullanıcı Admin mi? ' . ($user->role === 'admin' ? 'EVET' : 'HAYIR'));
            Log::info('ID\'ler Eşleşti mi? (user->id == assignment->user_id): ' . ($user->id == $assignment->user_id ? 'EVET' : 'HAYIR'));
            // --- HATA AYIKLAMA SONU ---
            return $user->role === 'admin' || $user->id == $assignment->user_id;
        });
    }
}
