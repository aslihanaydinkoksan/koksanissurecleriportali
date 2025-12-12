<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Observers\UserObserver;
use App\Models\User;
use App\Models\Shipment;
use App\Models\ProductionPlan;
use App\Models\Event;
use App\Models\VehicleAssignment;
use App\Models\Travel;
use App\Models\MaintenancePlan;
use App\Observers\BusinessUnitObserver;
class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Paginator::useBootstrap();

        // --- 1. AKILLI YENİLEME HASH'İ ---
        try {
            if (!app()->runningInConsole()) {
                // Eğer SystemController yoksa burası hata verebilir, geçici olarak try-catch içinde kalsın
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
                // Modelin namespace'ini tam yazdığından emin ol
                $maintenanceQuery = \App\Models\MaintenancePlan::where('status', 'pending_approval');

                if ($user->role !== 'admin') {
                    // User modelinde isManagerOrDirector fonksiyonu olduğundan emin olmalısın
                    if (method_exists($user, 'isManagerOrDirector') && $user->isManagerOrDirector() && $user->department_id) {
                        $maintenanceQuery->whereHas('user', fn($q) => $q->where('department_id', $user->department_id));
                    } else {
                        // Yetkisi yoksa hiçbir şey gösterme
                        $maintenanceQuery->where('id', 0);
                    }
                }
                $totalPending = $maintenanceQuery->count();
            }
            $view->with('globalPendingCount', $totalPending);
        });
        User::observe(UserObserver::class);
        Shipment::observe(BusinessUnitObserver::class);
        ProductionPlan::observe(BusinessUnitObserver::class);
        Event::observe(BusinessUnitObserver::class);
        VehicleAssignment::observe(BusinessUnitObserver::class);
        Travel::observe(BusinessUnitObserver::class);
        MaintenancePlan::observe(BusinessUnitObserver::class);
    }
}