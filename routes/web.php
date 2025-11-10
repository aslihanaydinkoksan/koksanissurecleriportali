<?php

use App\Http\Controllers\GeneralCalendarController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\BirimController;
use App\Http\Controllers\ProductionPlanController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleAssignmentController;
use App\Http\Controllers\ServiceScheduleController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerMachineController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\TestResultController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DepartmentController;

// Ana sayfa yönlendirmesi
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('welcome');
    }
    return redirect()->route('login');
});

// Kimlik doğrulama rotaları (kayıt kapalı)
Auth::routes(['register' => false]);

// Dashboard (Ana Panel) rotası
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/welcome', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/statistics', [HomeController::class, 'showStatistics'])->name('statistics.index');
Route::get('/important-items', [HomeController::class, 'showAllImportant'])->name('important.all');

// --- KULLANICI YÖNETİMİ ROTALARI ---
Route::middleware(['auth', 'role:admin,yönetici'])->prefix('users')->group(function () {
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// --- SEVKİYAT YÖNETİMİ ROTALARI ---
Route::middleware(['auth'])->prefix('shipments')->group(function () {
    Route::get('/create', [ShipmentController::class, 'create'])->name('shipments.create');
    Route::post('/', [ShipmentController::class, 'store'])->name('shipments.store');
    Route::get('/{shipment}/edit', [ShipmentController::class, 'edit'])->name('shipments.edit');
    Route::put('/{shipment}', [ShipmentController::class, 'update'])->name('shipments.update');
    Route::get('/{shipment}/export', [ShipmentController::class, 'export'])->name('shipments.export');
    Route::delete('/{shipment}', [ShipmentController::class, 'destroy'])->name('shipments.destroy');
    Route::post('/{shipment}/onayla', [ShipmentController::class, 'onayla'])->name('shipments.onayla');
    Route::post('/{shipment}/onayi-geri-al', [ShipmentController::class, 'onayiGeriAl'])->name('shipments.onayiGeriAl');
});

// --- İTHALAT/İHRACAT ROTALARI ---
Route::middleware(['auth'])->prefix('products')->group(function () {
    Route::get('/products', [ShipmentController::class, 'listAllFiltered'])->name('products.list');
});

// --- PROFİL YÖNETİMİ ROTALARI ---
Route::middleware(['auth'])->prefix('profile')->group(function () {
    Route::get('/edit', [UserController::class, 'profileEdit'])->name('profile.edit');
    Route::put('/', [UserController::class, 'profileUpdate'])->name('profile.update');
});

// --- BİRİM YÖNETİMİ ROTALARI ---
Route::middleware(['auth', 'role:admin'])->prefix('birimler')->group(function () {
    Route::get('/', [BirimController::class, 'index'])->name('birimler.index');
    Route::post('/', [BirimController::class, 'store'])->name('birimler.store');
    Route::delete('/{birim}', [BirimController::class, 'destroy'])->name('birimler.destroy');
});

Route::middleware('auth')->group(function () {
    Route::middleware('can:access-admin-features')->group(function () {
        Route::resource('departments', DepartmentController::class)
            ->except(['show']);
    });
});

// --- ÜRETİM BİRİMİ ROTALARI ---
Route::middleware(['auth'])->prefix('production')->name('production.')->group(function () {
    // /production/plans -> Planları listeleme sayfası
    Route::get('/plans', [ProductionPlanController::class, 'index'])->name('plans.index');
    // /production/plans/create -> Yeni plan oluşturma formunu göster
    Route::get('/plans/create', [ProductionPlanController::class, 'create'])->name('plans.create');
    // POST /production/plans -> Yeni planı veritabanına kaydet
    Route::post('/plans', [ProductionPlanController::class, 'store'])->name('plans.store');
    // /production/plans/{plan}/edit -> Düzenleme formunu göster
    // Not: Rota parametresi 'productionPlan' olmalı, model adıyla eşleşmeli
    Route::get('/plans/{productionPlan}/edit', [ProductionPlanController::class, 'edit'])->name('plans.edit');
    // PUT/PATCH /production/plans/{plan} -> Güncelle
    Route::put('/plans/{productionPlan}', [ProductionPlanController::class, 'update'])->name('plans.update');
    // DELETE /production/plans/{plan} -> Sil
    Route::delete('/plans/{productionPlan}', [ProductionPlanController::class, 'destroy'])->name('plans.destroy');
});

// --- HİZMET BİRİMİ ROTALARI ---
Route::middleware(['auth'])->prefix('service')->name('service.')->group(function () {

    // Etkinlik Yönetimi (Events)
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::resource('vehicles', VehicleController::class);
    Route::resource('assignments', VehicleAssignmentController::class);
    Route::resource('schedules', ServiceScheduleController::class);
});

// --- Genel KÖKSAN Takvimi Rotası (Giriş yapmış herkes erişebilir) ---
Route::middleware('auth')->group(function () {
    Route::get('/general-calendar', [GeneralCalendarController::class, 'showCalendar'])->name('general.calendar');
    Route::get('/calendar-events-data', [GeneralCalendarController::class, 'getEvents'])->name('web.calendar.events');
    Route::post('/calendar/toggle-important', [GeneralCalendarController::class, 'toggleImportant'])->name('calendar.toggleImportant');
});

// --- Müşteri Ziyaretleri Rotaları ---
Route::middleware('auth')->group(function () {
    // Ana Müşteri Rotaları (index, create, show, vb.)
    Route::resource('customers', CustomerController::class);
    // Bu, /customers/{customer}/machines URL'sini oluşturur
    Route::resource('customers.machines', CustomerMachineController::class)
        ->shallow() // Sadece 'customer_id' gereken rotaları (store, update, destroy) oluştur
        ->except(['index', 'show']); // Bu sayfalara ihtiyacımız yok
    // /customers/{customer}/complaints
    Route::resource('customers.complaints', ComplaintController::class)
        ->shallow()
        ->except(['index', 'show']);
    // /customers/{customer}/test-results
    Route::resource('customers.test-results', TestResultController::class)
        ->shallow()
        ->except(['index', 'show']);
    //    Seçilen müşteriye ait makineleri JSON olarak döndürür
    Route::get('/api/customers/{customer}/machines', [CustomerController::class, 'getMachinesJson'])
        ->name('api.customers.machines');
    Route::resource('travels', TravelController::class);
    Route::resource('travels.bookings', BookingController::class)
        ->shallow()
        ->except(['index', 'show']);
});
