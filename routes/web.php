<?php

use App\Http\Controllers\ApprovalStatusController;
use App\Http\Controllers\BusinessStateController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\DevelopmentImageController;
use App\Http\Controllers\DevelopmentController;
use App\Http\Controllers\DevelopmentFileController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadNotesController;
use App\Http\Controllers\LeadSourcesController;
use App\Http\Controllers\LeadStatusController;
use App\Http\Controllers\RoleController;


use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// NO AUTH Routes
Route::get('/', [DashboardController::class, 'index']);

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // AUTH Routes
    Route::get('/dashboard', function () {return Inertia::render('Dashboard');})->name('dashboard');
    Route::resource('approvalstatus', ApprovalStatusController::class);
    Route::resource('businessstate', BusinessStateController::class);
    Route::resource('cities', CityController::class);
    Route::resource('countries', CountryController::class);
    Route::resource('developers', DeveloperController::class);
    Route::resource('developmentimages', DevelopmentImageController::class);
    Route::resource('development', DevelopmentController::class);
    Route::resource('developmentfile', DevelopmentFileController::class);
    Route::resource('documenttype', DocumentTypeController::class);
    Route::resource('leads', LeadController::class);
    Route::resource('leadnotes', LeadNotesController::class);
    Route::resource('leadsources', LeadSourcesController::class);
    Route::resource('leadstatus', LeadStatusController::class);
    Route::resource('roles', RoleController::class);

});
