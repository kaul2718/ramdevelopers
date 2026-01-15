<?php

use App\Http\Controllers\ApprovalStatusController;
use App\Http\Controllers\BusinessStateController;
use App\Http\Controllers\CommercialStatusController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\DevelopmentController;
use App\Http\Controllers\PublicDevelopmentController;
use App\Http\Controllers\DevelopmentCaptorController;
use App\Http\Controllers\DevelopmentFileController;
use App\Http\Controllers\DevelopmentImageController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\HousingTypeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadNoteController;
use App\Http\Controllers\LeadSourcesController;
use App\Http\Controllers\LeadStatusController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\Country;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// NO AUTH Routes - Landing Page
Route::get('/', function () {
    return Inertia::render('Landing');
})->name('landing');

// Public API endpoints for landing page
Route::get('/api/developments/featured', [PublicDevelopmentController::class, 'featured'])->name('developments.featured');
Route::get('/api/developers/featured', [PublicDevelopmentController::class, 'featuredDevelopers'])->name('developers.featured');

// Public Developments Listing Page
Route::get('/developments', [PublicDevelopmentController::class, 'listing'])->name('developments.public');
Route::get('/developments/{devt_slug}', [PublicDevelopmentController::class, 'show'])->name('development.public.show');
Route::get('/developments/{devt_slug}/download-file/{file_id}', [PublicDevelopmentController::class, 'downloadFile'])->name('development.file.download');

// Ruta personalizada para el registro con datos adicionales
Route::get('/register', function () {
    $countries = Country::where('ctry_active', true)->orderBy('ctry_name')->get();
    return Inertia::render('Auth/Register', [
        'countries' => $countries
    ]);
})->middleware('guest')->name('register');
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', \App\Http\Middleware\CheckUserActive::class])->group(function () {
    // AUTH Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('approvalstatus', ApprovalStatusController::class);
    Route::resource('businessstate', BusinessStateController::class);
    Route::resource('commercialstatus', CommercialStatusController::class);
    Route::resource('cities', CityController::class);
    Route::resource('countries', CountryController::class);
    Route::resource('currency', CurrencyController::class);
    Route::resource('developers', DeveloperController::class);
    Route::resource('developmentimages', DevelopmentImageController::class);
    Route::resource('development', DevelopmentController::class);
    Route::resource('developmentfile', DevelopmentFileController::class);
    
    // Rutas para Development Captors - Nested Resource
    Route::get('development/{development}/captors', [DevelopmentCaptorController::class, 'index'])->name('development.captors.index');
    Route::post('development/{development}/captors', [DevelopmentCaptorController::class, 'store'])->name('development.captors.store');
    Route::get('development/{development}/captors/{userId}', [DevelopmentCaptorController::class, 'show'])->name('development.captors.show');
    Route::put('development/{development}/captors/{userId}', [DevelopmentCaptorController::class, 'update'])->name('development.captors.update');
    Route::delete('development/{development}/captors/{userId}', [DevelopmentCaptorController::class, 'destroy'])->name('development.captors.destroy');
    Route::patch('development/{development}/captors/{userId}/set-main', [DevelopmentCaptorController::class, 'setMain'])->name('development.captors.setMain');
    Route::get('development/{development}/captors-available-list', [DevelopmentCaptorController::class, 'getAvailableUsers'])->name('development.captors.availableUsers');
    
    Route::resource('documenttype', DocumentTypeController::class);
    Route::resource('housingtype', HousingTypeController::class);
    Route::resource('lead', LeadController::class);
    Route::resource('leadnote', LeadNoteController::class);
    Route::resource('leadsources', LeadSourcesController::class);
    Route::resource('leadstatus', LeadStatusController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);

    // Admin panel (grid of admin options)
    Route::get('/admin', function () {
        return Inertia::render('Admin/Index');
    })->name('admin');

});
