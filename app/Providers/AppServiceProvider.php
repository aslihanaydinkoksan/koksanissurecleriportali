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
use App\Observers\KanbanModelObserver;
use App\Models\Customer;
use App\Observers\CustomerObserver;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(\App\Services\Contracts\AIServiceInterface::class, \App\Services\GeminiService::class);
    }

    public function boot()
    {
        Paginator::useBootstrap();

        // Enforce Morph Maps for Polymorphic Relationships (Loggable Trait Uyumlu)
        Relation::enforceMorphMap([
            'user' => 'App\Models\User',
            'team' => 'App\Models\Team',
            'company' => 'App\Models\Vehicle',
            'logistics' => 'App\Models\LogisticsVehicle',
            'department' => 'App\Models\Department',
            'customer' => 'App\Models\Customer',
            'shipment' => 'App\Models\Shipment',
            'production_plan' => 'App\Models\ProductionPlan',
            'maintenance_plan' => 'App\Models\MaintenancePlan',
            'event' => 'App\Models\Event',
            'assignment' => 'App\Models\VehicleAssignment',
            'travel' => 'App\Models\Travel',
            'customer_visit' => 'App\Models\CustomerVisit',
            'customer_contact' => 'App\Models\CustomerContact',
            'customer_product' => 'App\Models\CustomerProduct',
            'customer_sample' => 'App\Models\CustomerSample',
            'complaint' => 'App\Models\Complaint',
            'customer_return' => 'App\Models\CustomerReturn',
            'customer_machine' => 'App\Models\CustomerMachine',
            'test_result' => 'App\Models\TestResult',
            'opportunity' => 'App\Models\Opportunity',
            'birim' => 'App\Models\Birim',
            'maintenance_type' => 'App\Models\MaintenanceType',
        ]);

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
                    // User modelinde isManager fonksiyonu olduğundan emin olmalı
                    if (method_exists($user, 'isManager') && $user->isManager() && $user->department_id) {
                        $maintenanceQuery->whereHas('user', fn($q) => $q->where('department_id', $user->department_id));
                    } else {
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
        MaintenancePlan::observe(KanbanModelObserver::class);
        Shipment::observe(KanbanModelObserver::class);
        ProductionPlan::observe(KanbanModelObserver::class);
        Event::observe(KanbanModelObserver::class);
        Customer::observe(CustomerObserver::class);
    }
}