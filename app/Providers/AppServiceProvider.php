<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\MaintenancePlan;
use App\Http\Controllers\SystemController;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        // --- 1. AKILLI YENİLEME HASH'İ ---
        try {
            if (!app()->runningInConsole()) {
                $systemHash = \App\Http\Controllers\SystemController::calculateSystemHash();
                View::share('globalDataHash', $systemHash);
            }
        } catch (\Exception $e) {
            View::share('globalDataHash', '');
        }

        // --- 2. BİLDİRİM SAYILARI ---
        View::composer('*', function ($view) {
            $totalPending = 0;
            if (Auth::check()) {
                $user = Auth::user();
                $maintenanceQuery = \App\Models\MaintenancePlan::where('status', 'pending_approval');

                // Burada zaten role kontrolü yapıyorsun, aynısını aşağıda kullanacağız
                if ($user->role !== 'admin') {
                    if ($user->isManagerOrDirector() && $user->department_id) {
                        $maintenanceQuery->whereHas('user', fn($q) => $q->where('department_id', $user->department_id));
                    } else {
                        $maintenanceQuery->where('id', 0);
                    }
                }
                $totalPending = $maintenanceQuery->count();
            }
            $view->with('globalPendingCount', $totalPending);
        });

        // --- 3. YETKİ TANIMLAMALARI (DÜZELTİLDİ) ---

        // A. Global Manager (Admin) her kapıyı açar
        Gate::before(function ($user, $ability) {
            // DİKKAT: Burada "$user->can(...)" kullanırsan sonsuz döngü olur!
            // O yüzden doğrudan role sütununa bakıyoruz.
            if ($user->role === 'admin') {
                return true;
            }
        });

        // B. Rezervasyon Yönetimi Yetkisi
        Gate::define('manage_bookings', function ($user) {
            // Kullanıcı admin değilse, özel yetkisine (manage_bookings) bak
            return $user->hasPermission('manage_bookings');
        });

        // C. (Opsiyonel) Eski kodlarındaki 'is-global-manager' kontrolünü de
        // Gate::define ile tanımlayabilirsin ki kodların hata vermesin:
        Gate::define('is-global-manager', function ($user) {
            return $user->role === 'admin';
        });
    }
}