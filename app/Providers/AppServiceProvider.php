<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\MaintenancePlan;

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

        // Tüm view'lara 'globalPendingCount' değişkenini gönderir
        View::composer('*', function ($view) {
            $totalPending = 0;

            if (Auth::check()) {
                $user = Auth::user();

                // 1. BAKIM SORGUSU (Henüz execute etmiyoruz, query hazırlıyoruz)
                $maintenanceQuery = MaintenancePlan::where('status', 'pending_approval');

                // EĞER ADMİN DEĞİLSE -> DEPARTMAN FİLTRESİ EKLE
                if ($user->role !== 'admin') {
                    if ($user->isManagerOrDirector() && $user->department_id) {

                        // Bakım: Oluşturanın departmanı yöneticininkiyle aynı mı?
                        $maintenanceQuery->whereHas('user', fn($q) => $q->where('department_id', $user->department_id));


                    } else {
                        // Yönetici değilse veya departmanı yoksa 0 görsün (Sorguyu boşa yormayalım)
                        $maintenanceQuery->where('id', 0);
                    }
                }

                // Toplam Sayı = Bakım Sayısı + Sevkiyat Sayısı
                $totalPending = $maintenanceQuery->count();
            }

            // View tarafında $globalPendingCount olarak kullanacağız
            View::share('globalPendingCount', $totalPending);
        });
    }
}