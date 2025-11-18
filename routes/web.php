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
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ActivityLogController;

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

// --- DEPARTMAN ROTALARI ---
Route::middleware('auth')->group(function () {
    Route::middleware('can:access-admin-features')->group(function () {
        Route::resource('departments', DepartmentController::class)
            ->except(['show']);
    });
});

// --- ÜRETİM BİRİMİ ROTALARI ---
Route::middleware(['auth'])->prefix('production')->name('production.')->group(function () {
    Route::get('/plans', [ProductionPlanController::class, 'index'])->name('plans.index');
    Route::get('/plans/create', [ProductionPlanController::class, 'create'])->name('plans.create');
    Route::post('/plans', [ProductionPlanController::class, 'store'])->name('plans.store');
    Route::get('/plans/{productionPlan}/edit', [ProductionPlanController::class, 'edit'])->name('plans.edit');
    Route::put('/plans/{productionPlan}', [ProductionPlanController::class, 'update'])->name('plans.update');
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

    // Araç Yönetimi
    Route::resource('vehicles', VehicleController::class);

    // Araç Atama Yönetimi (GÜNCELLENDİ)
    Route::get('/assignments', [VehicleAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/create', [VehicleAssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assignments', [VehicleAssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}', [VehicleAssignmentController::class, 'show'])->name('assignments.show');
    Route::get('/assignments/{assignment}/edit', [VehicleAssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('/assignments/{assignment}', [VehicleAssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('/assignments/{assignment}', [VehicleAssignmentController::class, 'destroy'])->name('assignments.destroy');

    // YENİ: Görev durumu güncelleme (AJAX)
    Route::patch('/assignments/{assignment}/status', [VehicleAssignmentController::class, 'updateStatus'])
        ->name('assignments.update-status');

    // Sefer Zamanları Yönetimi
    Route::resource('schedules', ServiceScheduleController::class);
});

// --- Genel KÖKSAN Takvimi Rotası ---
Route::middleware('auth')->group(function () {
    Route::get('/general-calendar', [GeneralCalendarController::class, 'showCalendar'])->name('general.calendar');
    Route::get('/calendar-events-data', [GeneralCalendarController::class, 'getEvents'])->name('web.calendar.events');
    Route::post('/calendar/toggle-important', [GeneralCalendarController::class, 'toggleImportant'])->name('calendar.toggleImportant');
});

// --- Müşteri Ziyaretleri Rotaları ---
Route::middleware('auth')->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::resource('customers.machines', CustomerMachineController::class)
        ->shallow()
        ->except(['index', 'show']);
    Route::resource('customers.complaints', ComplaintController::class)
        ->shallow()
        ->except(['index', 'show']);
    Route::resource('customers.test-results', TestResultController::class)
        ->shallow()
        ->except(['index', 'show']);
    Route::get('/api/customers/{customer}/machines', [CustomerController::class, 'getMachinesJson'])
        ->name('api.customers.machines');
    Route::resource('travels', TravelController::class);
    Route::resource('travels.bookings', BookingController::class)
        ->shallow()
        ->except(['index', 'show']);
    Route::get('/my-assignments', [VehicleAssignmentController::class, 'myAssignments'])
        ->name('my-assignments.index');
});

// --- TAKIM YÖNETİMİ ROTALARI  ---
Route::middleware('auth')->prefix('teams')->name('teams.')->group(function () {
    // Temel CRUD
    Route::get('/', [TeamController::class, 'index'])->name('index');
    Route::get('/create', [TeamController::class, 'create'])->name('create');
    Route::post('/', [TeamController::class, 'store'])->name('store');
    Route::get('/{team}', [TeamController::class, 'show'])->name('show');
    Route::get('/{team}/edit', [TeamController::class, 'edit'])->name('edit');
    Route::put('/{team}', [TeamController::class, 'update'])->name('update');
    Route::delete('/{team}', [TeamController::class, 'destroy'])->name('destroy');

    // YENİ: Takım yönetim işlemleri
    Route::patch('/{team}/toggle-active', [TeamController::class, 'toggleActive'])->name('toggle-active');
    Route::post('/{team}/add-member', [TeamController::class, 'addMember'])->name('add-member');
    Route::delete('/{team}/remove-member', [TeamController::class, 'removeMember'])->name('remove-member');

    // YENİ: Kullanıcının takımlarını getir (AJAX)
    Route::get('/user/{user}/teams', [TeamController::class, 'getUserTeams'])->name('user.teams');
});

// --- BENİM GÖREVLERİM ROTASI (GÜNCELLENDİ) ---
Route::middleware('auth')->group(function () {
    Route::get('/my-tasks', [VehicleAssignmentController::class, 'myTasks'])->name('my.tasks');
});

// --- SİSTEM LOGLARI (Sadece Global Manager) ---
Route::middleware(['auth', 'can:is-global-manager'])->group(function () {
    Route::get('/system/logs', [ActivityLogController::class, 'index'])->name('logs.index');
});