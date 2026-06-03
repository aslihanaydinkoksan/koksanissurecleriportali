<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneralCalendarController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->get('/calendar-events', [GeneralCalendarController::class, 'getEvents'])->name('api.calendar.events');

// Customer Sync from iaa_projesi
Route::get('/business-units', [App\Http\Controllers\Api\CustomerSyncController::class, 'getBusinessUnits']);
Route::post('/customers/sync', [App\Http\Controllers\Api\CustomerSyncController::class, 'sync']);
Route::post('/users/sync', [App\Http\Controllers\Api\CustomerSyncController::class, 'sync']);
Route::post('/complaints/delete', [App\Http\Controllers\Api\CustomerSyncController::class, 'deleteComplaint']);
Route::post('/complaints/restore', [App\Http\Controllers\Api\CustomerSyncController::class, 'restoreComplaint']);
Route::get('/customers/visit-data', [App\Http\Controllers\Api\CustomerSyncController::class, 'getVisitData']);
Route::get('/visits/stats', [App\Http\Controllers\Api\CustomerSyncController::class, 'getVisitStats']);
Route::get('/visits/list', [App\Http\Controllers\Api\CustomerSyncController::class, 'getVisitsList']);
Route::post('/customers/store-visit', [App\Http\Controllers\Api\CustomerSyncController::class, 'storeVisit']);
Route::post('/visit/toggle-lock', [App\Http\Controllers\Api\CustomerSyncController::class, 'toggleVisitLock']);
