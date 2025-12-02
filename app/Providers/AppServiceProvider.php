<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\MaintenancePlan;
use App\Http\Controllers\SystemController;

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

        // --- 1. AKILLI YENİLEME HASH'İ (OPTİMİZE EDİLDİ) ---
        // Composer içine değil, direkt boot içine yazıyoruz.
        // Böylece sayfa yüklenirken sadece 1 kere hesaplanır ve herkese dağıtılır.
        try {
            // Konsol komutlarında (migrate vb.) hata vermemesi için try-catch
            if (!app()->runningInConsole()) {
                $systemHash = \App\Http\Controllers\SystemController::calculateSystemHash();
                View::share('globalDataHash', $systemHash);
            }
        } catch (\Exception $e) {
            // Veritabanı henüz yoksa veya hata varsa boş geç
            View::share('globalDataHash', '');
        }


        // --- 2. BİLDİRİM SAYILARI (Mevcut Kodun) ---
        // Auth kontrolü gerektiği için bu mecburen composer içinde kalmalı.
        // Ancak '*' yerine sadece ana layout'lara verirsek daha hızlı çalışır.
        // Şimdilik '*' kalsın ama yavaşlık devam ederse 'layouts.app' yapacağız.
        View::composer('*', function ($view) {
            $totalPending = 0;

            if (Auth::check()) {
                $user = Auth::user();

                $maintenanceQuery = \App\Models\MaintenancePlan::where('status', 'pending_approval');

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
    }
}