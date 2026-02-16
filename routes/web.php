<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controller Importları
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
use App\Http\Controllers\MaintenanceAssetController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Approvals\MaintenanceApprovalController;
use App\Http\Controllers\GeneralCalendarController;
use App\Http\Controllers\TvDashboardController;
use App\Http\Controllers\SystemController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\CustomFieldDefinitionController;
use App\Http\Controllers\KanbanBoardController;
use App\Http\Controllers\KanbanViewController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CustomerVisitController;
use App\Services\KanbanService;
use App\Models\Event;
use App\Models\Travel;
use App\Http\Controllers\ScheduledReportController;
use App\Models\CustomerReturn;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. GENEL ROTALAR (Auth Gerektirmeyenler) ---

Route::get('/', [HomeController::class, 'welcome'])->name('index');

Auth::routes(['register' => false]);

Route::match(['get', 'post'], '/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout.get');

Route::view('/kvkk-aydinlatma-metni', 'auth.kvkk')->name('kvkk.show');

// --- 2. TV DASHBOARD (Auth Gerekmez veya Özel Auth) ---
Route::get('/tv-dashboard', [TvDashboardController::class, 'index'])->name('tv.dashboard');
Route::get('/tv/check-updates', [TvDashboardController::class, 'checkUpdates'])->name('tv.check_updates');


// ==================================================================================
//  AUTH MIDDLEWARE GRUBU (Tüm İç Sayfalar)
// ==================================================================================
Route::middleware(['auth'])->group(function () {
    Route::post('/ai/ask', [App\Http\Controllers\AIController::class, 'ask'])->name('ai.ask');

    // --- DASHBOARD VE ANA SAYFALAR ---
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/welcome', [HomeController::class, 'welcome'])->name('welcome');
    Route::post('/switch-unit', [HomeController::class, 'switchUnit'])->name('switch.unit');

    // --- SİSTEM KONTROLLERİ ---
    Route::get('/system/check-updates', [SystemController::class, 'checkUpdates'])->name('system.check_updates');
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/finance', [App\Http\Controllers\StatisticsController::class, 'financeDashboard'])
        ->name('statistics.finance')
        ->middleware(['auth', 'role:admin|yonetici']);

    // --- BİLDİRİMLER ---
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/check', [HomeController::class, 'checkNotifications'])->name('check');
        Route::get('/read-all', [HomeController::class, 'readAllNotifications'])->name('readAll');
        Route::get('/{id}/read', [HomeController::class, 'readNotification'])->name('read');
    });

    // --- DOSYA İŞLEMLERİ (Polimorfik) ---
    Route::post('/files/upload', [FileController::class, 'store'])->name('files.store');
    Route::delete('/files/{file}', [FileController::class, 'destroy'])->name('files.destroy');
    Route::get('/files/{file}/download', [FileController::class, 'download'])->name('files.download');

    // --- GENEL TAKVİM ---
    Route::get('/general-calendar', [GeneralCalendarController::class, 'showCalendar'])->name('general.calendar');
    Route::get('/calendar-events-data', [GeneralCalendarController::class, 'getEvents'])->name('web.calendar.events');
    Route::post('/calendar/toggle-important', [GeneralCalendarController::class, 'toggleImportant'])->name('calendar.toggleImportant');
    Route::get('/important-items', [HomeController::class, 'showAllImportant'])->name('important.all');

    // --- KANBAN PANOSU YÖNETİMİ ---
    Route::get('/kanban/board/{board_id}', [KanbanViewController::class, 'index'])->name('kanban.board');
    Route::get('/kanban/smart-redirect', [KanbanBoardController::class, 'checkAndRedirect'])->name('kanban.smart_redirect');
    Route::resource('kanban-boards', KanbanBoardController::class)->except(['show']);
    Route::get('/kanban/card/{kanbanCard}', [KanbanViewController::class, 'show'])->name('kanban.show');
    Route::post('/kanban/move-card', [KanbanViewController::class, 'moveCard'])->name('kanban.move');

    // ==========================================================
    //  DEPARTMAN BAZLI YÖNETİMLER
    // ==========================================================

    // 1. KULLANICI & YETKİ YÖNETİMİ (Sadece 'manage_users' yetkisi olanlar)
    // Not: Spatie'de birden fazla rol kontrolü için | (pipe) kullanılır.
    Route::middleware(['role:admin|yönetici'])->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('birimler', BirimController::class)->only(['index', 'store', 'destroy']);
        Route::resource('departments', DepartmentController::class)->except(['show']);
        Route::resource('business-units', \App\Http\Controllers\BusinessUnitController::class)
            ->only(['index', 'store', 'destroy']);

        // Loglar (Sadece Admin veya Global Manager)
        Route::get('/system/logs', [ActivityLogController::class, 'index'])
            ->middleware('can:is-global-manager') // Veya role:admin
            ->name('logs.index');

        Route::middleware(['role:admin|yönetici'])
            ->prefix('admin')
            ->name('admin.')
            ->group(function () {
                Route::resource('custom-fields', CustomFieldDefinitionController::class);
            });

        Route::get('/report-settings/create', [ScheduledReportController::class, 'create'])->name('report-settings.create');
        Route::post('/report-settings', [ScheduledReportController::class, 'store'])->name('report-settings.store');
        Route::get('/report-settings', [ScheduledReportController::class, 'index'])->name('report-settings.index');
        Route::get('/report-settings/create', [ScheduledReportController::class, 'create'])->name('report-settings.create');
        Route::post('/report-settings', [ScheduledReportController::class, 'store'])->name('report-settings.store');
        Route::get('/report-settings/{report}/edit', [ScheduledReportController::class, 'edit'])->name('report-settings.edit');
        Route::put('/report-settings/{report}', [ScheduledReportController::class, 'update'])->name('report-settings.update');
        Route::post('/report-settings/{report}/toggle', [ScheduledReportController::class, 'toggleStatus'])->name('report-settings.toggle');
        Route::delete('/report-settings/{report}', [ScheduledReportController::class, 'destroy'])->name('report-settings.destroy');
    });

    // Profil (Herkes)
    Route::prefix('profile')->group(function () {
        Route::get('/edit', [UserController::class, 'profileEdit'])->name('profile.edit');
        Route::put('/', [UserController::class, 'profileUpdate'])->name('profile.update');
    });

    // 2. LOJİSTİK YÖNETİMİ
    Route::prefix('shipments')->name('shipments.')->group(function () {
        Route::get('/', [ShipmentController::class, 'listAllFiltered'])->name('index');

        // Standart CRUD İşlemleri
        Route::get('/create', [ShipmentController::class, 'create'])->name('create');
        Route::post('/', [ShipmentController::class, 'store'])->name('store');

        // DÜZELTME 1: Başındaki '/shipments' kalktı, name sadece 'show' oldu.
        // Sonuç URL: /shipments/{id} | Route Name: shipments.show
        Route::get('/{id}', [ShipmentController::class, 'show'])->name('show');

        Route::get('/{shipment}/edit', [ShipmentController::class, 'edit'])->name('edit');
        Route::put('/{shipment}', [ShipmentController::class, 'update'])->name('update');
        Route::delete('/{shipment}', [ShipmentController::class, 'destroy'])->name('destroy');

        // DÜZELTME 2: Durak İşlemleri (Controller yolunu kısalttım, yukarıda use ekleyebilirsin veya böyle kalabilir)
        // URL: /shipments/{id}/stops
        Route::post('/{id}/stops', [App\Http\Controllers\ShipmentStopController::class, 'store'])->name('stops.store');

        // URL: /shipments/stops/{id} (Burada shipment id değil, stop id silindiği için prefix mantıklı)
        Route::delete('/stops/{id}', [App\Http\Controllers\ShipmentStopController::class, 'destroy'])->name('stops.destroy');

        // Diğer İşlemler
        Route::get('/export-list', [ShipmentController::class, 'exportList'])->name('export_list');
        Route::get('/{shipment}/export', [ShipmentController::class, 'export'])->name('export');
        Route::post('/{shipment}/onayla', [ShipmentController::class, 'onayla'])->name('onayla');
        Route::post('/{shipment}/onayi-geri-al', [ShipmentController::class, 'onayiGeriAl'])->name('onayiGeriAl');
    });

    // Ürün Listesi 
    Route::get('/products/products', [ShipmentController::class, 'listAllFiltered'])->name('products.list');


    // 3. ÜRETİM YÖNETİMİ
    Route::prefix('production')->name('production.')->group(function () {
        Route::get('/plans/export-list', [ProductionPlanController::class, 'exportList'])->name('plans.export_list');
        Route::get('/plans/{plan}/export', [ProductionPlanController::class, 'export'])->name('plans.export');
        Route::resource('plans', ProductionPlanController::class);
    });


    // 4. BAKIM YÖNETİMİ
    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        // Varlıklar
        Route::get('/assets/export', [MaintenanceAssetController::class, 'export'])->name('assets.export');
        Route::resource('assets', MaintenanceAssetController::class);

        // Planlar - Custom Exportlar Resource'dan önce gelmeli
        Route::get('/plans/export-list', [MaintenanceController::class, 'exportList'])->name('export_list');
        Route::get('/plans/{maintenance_plan}/export', [MaintenanceController::class, 'export'])->name('export');

        // Plan Resource (Sıralamaya Dikkat: Show, Edit vs. ID çakışmaması için)
        Route::get('/', [MaintenanceController::class, 'index'])->name('index');
        Route::get('/create', [MaintenanceController::class, 'create'])->name('create');
        Route::post('/', [MaintenanceController::class, 'store'])->name('store');
        Route::get('/{maintenance_plan}', [MaintenanceController::class, 'show'])->name('show');
        Route::get('/{maintenance_plan}/edit', [MaintenanceController::class, 'edit'])->name('edit');
        Route::put('/{maintenance_plan}', [MaintenanceController::class, 'update'])->name('update');
        Route::delete('/{maintenance_plan}', [MaintenanceController::class, 'destroy'])->name('destroy');

        // İşlemler
        Route::post('/{id}/start-timer', [MaintenanceController::class, 'startTimer'])->name('start-timer');
        Route::post('/{id}/stop-timer', [MaintenanceController::class, 'stopTimer'])->name('stop-timer');
        Route::post('/{id}/upload-file', [MaintenanceController::class, 'uploadFile'])->name('upload-file');
        Route::delete('/file/{file_id}', [MaintenanceController::class, 'deleteFile'])->name('delete-file');
    });

    // Bakım Onayları
    Route::prefix('approvals')->name('approvals.')->group(function () {
        Route::get('/maintenance', [MaintenanceApprovalController::class, 'index'])->name('maintenance');
    });


    // 5. HİZMET & İDARİ İŞLER YÖNETİMİ
    Route::prefix('service')->name('service.')->group(function () {

        // Etkinlikler
        Route::get('/events/export', [EventController::class, 'export'])->name('events.export');
        Route::patch('/events/{event}/status', [EventController::class, 'updateStatus'])->name('events.update-status');
        Route::resource('events', EventController::class);

        // Etkinlik Rezervasyonu (Polimorfik)
        Route::post('/events/{model}/bookings', [BookingController::class, 'store'])
            ->defaults('model_type', Event::class)
            ->name('events.bookings.store');

        // Araçlar
        Route::resource('vehicles', VehicleController::class);
        Route::resource('logistics-vehicles', LogisticsVehicleController::class);

        Route::post('/vehicle-assignments', [App\Http\Controllers\VehicleAssignmentController::class, 'store'])->name('vehicle-assignments.store');
Route::delete('/vehicle-assignments/{id}', [App\Http\Controllers\VehicleAssignmentController::class, 'destroy'])->name('vehicle-assignments.destroy');
        // Araç Görevlendirme
        Route::get('/assignments/export', [VehicleAssignmentController::class, 'export'])->name('assignments.export');
        Route::get('/assignments/{assignment}/export-detail', [VehicleAssignmentController::class, 'exportDetail'])->name('assignments.export_detail');
        Route::resource('assignments', VehicleAssignmentController::class);

        // Araç Atama & Durum
        Route::put('/assignments/{assignment}/assign', [VehicleAssignmentController::class, 'assignVehicle'])->name('assignments.assign');
        Route::patch('/assignments/{assignment}/status', [VehicleAssignmentController::class, 'updateStatus'])->name('assignments.update-status');
        Route::get('/assigned-by-me', [VehicleAssignmentController::class, 'assignedByMe'])->name('assignments.created_by_me');

        // Genel Görevler (Araçsız)
        Route::prefix('general-tasks')->name('general-tasks.')->group(function () {
            Route::get('/', [VehicleAssignmentController::class, 'generalIndex'])->name('index');
            Route::get('/create', [VehicleAssignmentController::class, 'create'])->name('create');
            Route::post('/', [VehicleAssignmentController::class, 'store'])->name('store');
            Route::get('/{assignment}/edit', [VehicleAssignmentController::class, 'edit'])->name('edit');
            Route::put('/{assignment}', [VehicleAssignmentController::class, 'update'])->name('update');
            Route::delete('/{assignment}', [VehicleAssignmentController::class, 'destroy'])->name('destroy');
        });

        // Seferler
        Route::resource('schedules', ServiceScheduleController::class);
    });


    // 6. MÜŞTERİ, SEYAHAT ve TAKIM YÖNETİMİ

    // Müşteriler
    Route::resource('customers', CustomerController::class);
    Route::resource('customers.machines', CustomerMachineController::class)->shallow()->except(['index', 'show']);
    Route::resource('customers.complaints', ComplaintController::class)->shallow()->except(['index', 'show']);
    Route::resource('customers.test-results', TestResultController::class)->shallow()->except(['index', 'show']);
    Route::get('/api/customers/{customer}/machines', [CustomerController::class, 'getMachinesJson'])->name('api.customers.machines');
    Route::post('/customers/{customer}/activities', [CustomerController::class, 'storeActivity'])->name('customers.activities.store');
    Route::put('/customer-activities/{activity}', [App\Http\Controllers\CustomerController::class, 'updateActivity'])->name('customer-activities.update');
    Route::delete('/customer-activities/{activity}', [App\Http\Controllers\CustomerController::class, 'destroyActivity'])->name('customer-activities.destroy');
    Route::post('/customers/{customer}/returns', [App\Http\Controllers\CustomerController::class, 'storeReturn'])->name('customers.returns.store');
    Route::patch('/customer-returns/{customerReturn}/status', [App\Http\Controllers\CustomerController::class, 'updateReturnStatus'])
        ->name('customer-returns.update-status');
    Route::put('/customer-returns/{return}', [App\Http\Controllers\CustomerController::class, 'updateReturn'])->name('customer-returns.update');
    Route::delete('/customer-returns/{return}', [App\Http\Controllers\CustomerController::class, 'destroyReturn'])->name('customer-returns.destroy');
    // Customer Samples
    Route::post('/customers/{customer}/samples', [App\Http\Controllers\CustomerController::class, 'storeSample'])->name('customers.samples.store');
    Route::patch('/customer-samples/{customerSample}/status', [App\Http\Controllers\CustomerController::class, 'updateSampleStatus'])->name('customer-samples.update-status');
    Route::put('/customer-samples/{sample}', [App\Http\Controllers\CustomerController::class, 'updateSample'])->name('customer-samples.update');
    Route::delete('/customer-samples/{sample}', [App\Http\Controllers\CustomerController::class, 'destroySample'])->name('customer-samples.destroy');
    // Fırsatlar & Duyumlar
    Route::post('/customers/{customer}/opportunities', [App\Http\Controllers\OpportunityController::class, 'store'])->name('customers.opportunities.store');
    Route::patch('/opportunities/{opportunity}/stage', [App\Http\Controllers\OpportunityController::class, 'updateStage'])->name('opportunities.update-stage');
    Route::delete('/opportunities/{opportunity}', [App\Http\Controllers\OpportunityController::class, 'destroy'])->name('opportunities.destroy');
    Route::put('/opportunities/{opportunity}', [App\Http\Controllers\OpportunityController::class, 'update'])->name('opportunities.update');
    Route::post('/customers/{customer}/products', [App\Http\Controllers\CustomerController::class, 'storeProduct'])->name('customers.products.store');
    Route::delete('/customer-products/{product}', [App\Http\Controllers\CustomerController::class, 'destroyProduct'])->name('customer-products.destroy');
    Route::put('/customer-products/{product}', [App\Http\Controllers\CustomerController::class, 'updateProduct'])->name('customer-products.update');
    Route::post('/customers/{customer}/visits', [App\Http\Controllers\CustomerVisitController::class, 'store'])->name('customers.visits.store');
    Route::put('/customers/{customer}/visits/{visit}', [CustomerVisitController::class, 'update'])->name('customers.visits.update');
    Route::delete('/visits/{visit}', [App\Http\Controllers\CustomerVisitController::class, 'destroy'])->name('visits.destroy');
    Route::get('/visits/{visit}/print', [App\Http\Controllers\CustomerVisitController::class, 'print'])->name('visits.print');
    // Seyahatler
    Route::get('/travels/export', [TravelController::class, 'export'])->name('travels.export');
    Route::resource('travels', TravelController::class);
    Route::post('/travels/{model}/bookings', [BookingController::class, 'store'])
        ->defaults('model_type', Travel::class)
        ->name('travels.bookings.store');

    // Genel Rezervasyonlar
    Route::get('/bookings/export', [BookingController::class, 'export'])->name('bookings.export');
    Route::resource('bookings', BookingController::class)->except(['create', 'store']);
    // --- MASRAF YÖNETİMİ 
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Takım Yönetimi
    Route::resource('teams', TeamController::class);
    Route::prefix('teams')->name('teams.')->group(function () {
        Route::patch('/{team}/toggle-active', [TeamController::class, 'toggleActive'])->name('toggle-active');
        Route::post('/{team}/add-member', [TeamController::class, 'addMember'])->name('add-member');
        Route::delete('/{team}/remove-member', [TeamController::class, 'removeMember'])->name('remove-member');
        Route::get('/user/{user}/teams', [TeamController::class, 'getUserTeams'])->name('user.teams');
    });

    // Bireysel
    Route::get('/my-assignments', [VehicleAssignmentController::class, 'myAssignments'])->name('my-assignments.index');
    Route::get('/my-tasks', [VehicleAssignmentController::class, 'myTasks'])->name('my.tasks');

    // --- TO-DO LIST (YENİ) ---
    Route::post('/todos', [App\Http\Controllers\TodoController::class, 'store'])->name('todos.store');
    Route::post('/todos/{todo}/toggle', [App\Http\Controllers\TodoController::class, 'toggle'])->name('todos.toggle');
    Route::delete('/todos/{todo}', [App\Http\Controllers\TodoController::class, 'destroy'])->name('todos.destroy');
}); // End of Auth Middleware
