<?php

use App\Http\Controllers\GeneralCalendarController;
use App\Models\MaintenancePlan;
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
use App\Http\Controllers\LogisticsVehicleController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\MaintenanceAssetController; // Bunu eklemeyi unutma
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Approvals\MaintenanceApprovalController;
use App\Models\Event;
use App\Models\EventType;
use App\Models\Travel;

// Ana sayfa yönlendirmesi
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->email === 'tv@koksan.com'
            ? redirect()->route('tv.dashboard')
            : view('welcome');
    }
    return redirect()->route('login');
});

// Kimlik doğrulama rotaları
Auth::routes(['register' => false]);
Route::match(['get', 'post'], '/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Rol yönetimi
Route::resource('roles', RoleController::class);

// Dashboard rotaları
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/welcome', [HomeController::class, 'welcome'])->name('welcome');
Route::get('/statistics', [App\Http\Controllers\StatisticsController::class, 'index'])->name('statistics.index');
Route::get('/important-items', [HomeController::class, 'showAllImportant'])->name('important.all');


// --- KULLANICI YÖNETİMİ ---
Route::middleware(['auth', 'role:admin,yönetici'])->group(function () {
    Route::resource('users', UserController::class);
});

// --- SEVKİYAT YÖNETİMİ ---
Route::middleware(['auth'])->prefix('shipments')->group(function () {
    Route::get('/create', [ShipmentController::class, 'create'])->name('shipments.create');
    Route::post('/', [ShipmentController::class, 'store'])->name('shipments.store');
    Route::get('/{shipment}/edit', [ShipmentController::class, 'edit'])->name('shipments.edit');
    Route::put('/{shipment}', [ShipmentController::class, 'update'])->name('shipments.update');
    Route::get('/export-list', [ShipmentController::class, 'exportList'])->name('shipments.export_list');
    // MEVCUT: Tekil Sevkiyat Export
    Route::get('/{shipment}/export', [ShipmentController::class, 'export'])->name('shipments.export');
    Route::delete('/{shipment}', [ShipmentController::class, 'destroy'])->name('shipments.destroy');
    Route::post('/{shipment}/onayla', [ShipmentController::class, 'onayla'])->name('shipments.onayla');
    Route::post('/{shipment}/onayi-geri-al', [ShipmentController::class, 'onayiGeriAl'])->name('shipments.onayiGeriAl');
});

// --- İTHALAT/İHRACAT ---
Route::middleware(['auth'])->prefix('products')->group(function () {
    Route::get('/products', [ShipmentController::class, 'listAllFiltered'])->name('products.list');
});

// --- PROFİL YÖNETİMİ ---
Route::middleware(['auth'])->prefix('profile')->group(function () {
    Route::get('/edit', [UserController::class, 'profileEdit'])->name('profile.edit');
    Route::put('/', [UserController::class, 'profileUpdate'])->name('profile.update');
});

// --- BİRİM YÖNETİMİ ---
Route::middleware(['auth', 'role:admin'])->prefix('birimler')->group(function () {
    Route::get('/', [BirimController::class, 'index'])->name('birimler.index');
    Route::post('/', [BirimController::class, 'store'])->name('birimler.store');
    Route::delete('/{birim}', [BirimController::class, 'destroy'])->name('birimler.destroy');
});

// --- DEPARTMAN ---
Route::middleware('auth')->group(function () {
    Route::middleware('can:access-admin-features')->group(function () {
        Route::resource('departments', DepartmentController::class)->except(['show']);
    });
});

// --- ÜRETİM BİRİMİ ---
Route::middleware(['auth'])->prefix('production')->name('production.')->group(function () {
    // 1. Liste
    Route::get('/plans/export-list', [ProductionPlanController::class, 'exportList'])->name('plans.export_list');
    // 2. Detay Fişi
    Route::get('/plans/{productionPlan}/export', [ProductionPlanController::class, 'export'])->name('plans.export');

    Route::get('/plans', [ProductionPlanController::class, 'index'])->name('plans.index');
    Route::get('/plans/create', [ProductionPlanController::class, 'create'])->name('plans.create');
    Route::post('/plans', [ProductionPlanController::class, 'store'])->name('plans.store');
    Route::get('/plans/{productionPlan}/edit', [ProductionPlanController::class, 'edit'])->name('plans.edit');
    Route::put('/plans/{productionPlan}', [ProductionPlanController::class, 'update'])->name('plans.update');
    Route::delete('/plans/{productionPlan}', [ProductionPlanController::class, 'destroy'])->name('plans.destroy');
});

// --- HİZMET BİRİMİ ---
Route::middleware(['auth'])->prefix('service')->name('service.')->group(function () {

    // Etkinlik Yönetimi (Events)
    // YENİ: Etkinlik Listesi Export
    Route::get('/events/export', [EventController::class, 'export'])->name('events.export');

    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

    // Etkinlik Bazlı Rezervasyon
    Route::post('/events/{model}/bookings', [BookingController::class, 'store'])
        ->defaults('model_type', Event::class)
        ->name('events.bookings.store');

    // Araç Yönetimi
    Route::resource('vehicles', VehicleController::class);
    Route::resource('logistics-vehicles', LogisticsVehicleController::class);

    // 1. Liste (Mevcut export rotası liste olarak kalıyor)
    Route::get('/assignments/export', [VehicleAssignmentController::class, 'export'])->name('assignments.export');

    // 2. Detay Fişi (YENİ)
    Route::get('/assignments/{assignment}/export-detail', [VehicleAssignmentController::class, 'exportDetail'])->name('assignments.export_detail');

    Route::get('/assignments', [VehicleAssignmentController::class, 'index'])->name('assignments.index');
    Route::get('/assignments/create', [VehicleAssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assignments', [VehicleAssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}', [VehicleAssignmentController::class, 'show'])->name('assignments.show');
    Route::get('/assignments/{assignment}/edit', [VehicleAssignmentController::class, 'edit'])->name('assignments.edit');
    Route::put('/assignments/{assignment}', [VehicleAssignmentController::class, 'update'])->name('assignments.update');
    Route::delete('/assignments/{assignment}', [VehicleAssignmentController::class, 'destroy'])->name('assignments.destroy');
    Route::put('/assignments/{assignment}/assign', [VehicleAssignmentController::class, 'assignVehicle'])
        ->name('assignments.assign')
        ->middleware('auth');
    Route::patch('/assignments/{assignment}/status', [VehicleAssignmentController::class, 'updateStatus'])
        ->name('assignments.update-status');

    // --- GENEL GÖREVLER ---
    Route::prefix('general-tasks')->name('general-tasks.')->group(function () {
        Route::get('/', [VehicleAssignmentController::class, 'generalIndex'])->name('index');
        Route::get('/create', [VehicleAssignmentController::class, 'create'])->name('create');
        Route::post('/', [VehicleAssignmentController::class, 'store'])->name('store');
        Route::get('/{assignment}/edit', [VehicleAssignmentController::class, 'edit'])->name('edit');
        Route::put('/{assignment}', [VehicleAssignmentController::class, 'update'])->name('update');
        Route::delete('/{assignment}', [VehicleAssignmentController::class, 'destroy'])->name('destroy');
    });

    // Atadığım Görevler
    Route::get('/assigned-by-me', [VehicleAssignmentController::class, 'assignedByMe'])->name('assignments.created_by_me');

    // Sefer Zamanları
    Route::resource('schedules', ServiceScheduleController::class);
});

// --- BAKIM BİRİMİ ---
Route::middleware(['auth'])->prefix('maintenance')->name('maintenance.')->group(function () {

    // MAKİNE VE VARLIK YÖNETİMİ
    // YENİ: Varlık Listesi Export (Resource rotalarından önce tanımlanmalı)
    Route::get('/assets/export', [MaintenanceAssetController::class, 'export'])->name('assets.export');
    Route::resource('assets', MaintenanceAssetController::class);

    // BAKIM PLANLARI
    // 1. Liste
    Route::get('/plans/export-list', [MaintenancePlan::class, 'exportList'])->name('export_list'); // maintenance.export_list
    // 2. Detay Fişi
    Route::get('/plans/{maintenance_plan}/export', [MaintenancePlan::class, 'export'])->name('export'); // maintenance.export

    Route::get('/', [MaintenanceController::class, 'index'])->name('index');
    Route::get('/create', [MaintenanceController::class, 'create'])->name('create');
    Route::post('/', [MaintenanceController::class, 'store'])->name('store');
    Route::get('/{maintenance_plan}', [MaintenanceController::class, 'show'])->name('show');
    Route::get('/{maintenance_plan}/edit', [MaintenanceController::class, 'edit'])->name('edit');
    Route::put('/{maintenance_plan}', [MaintenanceController::class, 'update'])->name('update');
    Route::delete('/{maintenance_plan}', [MaintenanceController::class, 'destroy'])->name('destroy');

    // Timer İşlemleri
    Route::post('/{id}/start-timer', [MaintenanceController::class, 'startTimer'])->name('start-timer');
    Route::post('/{id}/stop-timer', [MaintenanceController::class, 'stopTimer'])->name('stop-timer');

    // Dosya İşlemleri
    Route::post('/{id}/upload-file', [MaintenanceController::class, 'uploadFile'])->name('upload-file');
    Route::delete('/file/{file_id}', [MaintenanceController::class, 'deleteFile'])->name('delete-file');
});

// --- GENEL TAKVİM ---
Route::middleware('auth')->group(function () {
    Route::get('/general-calendar', [GeneralCalendarController::class, 'showCalendar'])->name('general.calendar');
    Route::get('/calendar-events-data', [GeneralCalendarController::class, 'getEvents'])->name('web.calendar.events');
    Route::post('/calendar/toggle-important', [GeneralCalendarController::class, 'toggleImportant'])->name('calendar.toggleImportant');
});

// --- MÜŞTERİ & SEYAHAT ---
Route::middleware('auth')->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::resource('customers.machines', CustomerMachineController::class)->shallow()->except(['index', 'show']);
    Route::resource('customers.complaints', ComplaintController::class)->shallow()->except(['index', 'show']);
    Route::resource('customers.test-results', TestResultController::class)->shallow()->except(['index', 'show']);
    Route::get('/api/customers/{customer}/machines', [CustomerController::class, 'getMachinesJson'])->name('api.customers.machines');

    // SEYAHATLER (Travel)
    // YENİ: Seyahat Listesi Export (Resource'dan önce)
    Route::get('/travels/export', [TravelController::class, 'export'])->name('travels.export');
    Route::resource('travels', TravelController::class);

    // Seyahat Bazlı Rezervasyon
    Route::post('/travels/{model}/bookings', [BookingController::class, 'store'])
        ->defaults('model_type', Travel::class)
        ->name('travels.bookings.store');

    Route::get('/my-assignments', [VehicleAssignmentController::class, 'myAssignments'])->name('my-assignments.index');
    Route::post('/customers/{customer}/activities', [CustomerController::class, 'storeActivity'])
        ->name('customers.activities.store')
        ->middleware('auth');
});

// --- TAKIM YÖNETİMİ ---
Route::middleware('auth')->prefix('teams')->name('teams.')->group(function () {
    Route::get('/', [TeamController::class, 'index'])->name('index');
    Route::get('/create', [TeamController::class, 'create'])->name('create');
    Route::post('/', [TeamController::class, 'store'])->name('store');
    Route::get('/{team}', [TeamController::class, 'show'])->name('show');
    Route::get('/{team}/edit', [TeamController::class, 'edit'])->name('edit');
    Route::put('/{team}', [TeamController::class, 'update'])->name('update');
    Route::delete('/{team}', [TeamController::class, 'destroy'])->name('destroy');
    Route::patch('/{team}/toggle-active', [TeamController::class, 'toggleActive'])->name('toggle-active');
    Route::post('/{team}/add-member', [TeamController::class, 'addMember'])->name('add-member');
    Route::delete('/{team}/remove-member', [TeamController::class, 'removeMember'])->name('remove-member');
    Route::get('/user/{user}/teams', [TeamController::class, 'getUserTeams'])->name('user.teams');
});

// --- BENİM GÖREVLERİM ---
Route::middleware('auth')->group(function () {
    Route::get('/my-tasks', [VehicleAssignmentController::class, 'myTasks'])->name('my.tasks');
});

// --- SİSTEM LOGLARI ---
Route::middleware(['auth', 'can:is-global-manager'])->group(function () {
    Route::get('/system/logs', [ActivityLogController::class, 'index'])->name('logs.index');
});

// --- ONAY SAYFALARI ---
Route::middleware(['auth'])->prefix('approvals')->name('approvals.')->group(function () {
    Route::get('/maintenance', [MaintenanceApprovalController::class, 'index'])->name('maintenance');
});

// --- Diğer Yardımcı Rotalar ---
Route::middleware(['auth'])->group(function () {
    Route::post('/calendar/toggle-important', [App\Http\Controllers\HomeController::class, 'toggleImportant'])->name('calendar.toggleImportant');
});
Route::get('/tv-dashboard', [App\Http\Controllers\TvDashboardController::class, 'index'])->name('tv.dashboard');
Route::get('/tv/check-updates', [App\Http\Controllers\TvDashboardController::class, 'checkUpdates'])->name('tv.check_updates');
Route::get('/notifications/{id}/read', [HomeController::class, 'readNotification'])->name('notifications.read');
Route::get('/notifications/read-all', [HomeController::class, 'readAllNotifications'])->name('notifications.readAll');
Route::get('/notifications/check', [HomeController::class, 'checkNotifications'])->name('notifications.check');
Route::view('/kvkk-aydinlatma-metni', 'auth.kvkk')->name('kvkk.show');
Route::post('/files/upload', [App\Http\Controllers\FileController::class, 'store'])->name('files.store');
Route::delete('/files/{file}', [App\Http\Controllers\FileController::class, 'destroy'])->name('files.destroy');
Route::get('/files/{file}/download', [App\Http\Controllers\FileController::class, 'download'])->name('files.download');

Route::middleware(['auth'])->group(function () {
    Route::get('/system/check-updates', [App\Http\Controllers\SystemController::class, 'checkUpdates'])->name('system.check_updates');
});

// --- GENEL REZERVASYONLAR (BOOKINGS) ---
Route::middleware(['auth'])->group(function () {
    // YENİ: Rezervasyon Listesi Export
    Route::get('/bookings/export', [BookingController::class, 'export'])->name('bookings.export');
    Route::resource('bookings', BookingController::class)->except(['create', 'store']);
});