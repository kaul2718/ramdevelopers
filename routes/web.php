<?php

use App\Http\Controllers\ApprovalStatusController;
use App\Http\Controllers\BusinessStateController;
use App\Http\Controllers\CommercialStatusController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\DevelopmentController;
use App\Http\Controllers\DevelopmentFileController;
use App\Http\Controllers\DevelopmentImageController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadNoteController;
use App\Http\Controllers\LeadSourcesController;
use App\Http\Controllers\LeadStatusController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// NO AUTH Routes
Route::get('/', function () {
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| RUTA RESET PASSWORD SOLO DESARROLLO
|--------------------------------------------------------------------------
*/
if (app()->environment('local')) {
    Route::get('/reset-password', function () {
        return Inertia::render('Auth/ResetPassword', [
            'token' => 'TOKEN_DE_PRUEBA',
            'email' => 'test@local.dev',
        ]);
    });
}

if (app()->environment('local')) {
    Route::get('/dev-verify-email', function () {
        return Inertia::render('Auth/VerifyEmail');
    });
}

if (app()->environment('local')) {
    Route::get('/two-factor-dev', function () {
        return Inertia::render('Auth/TwoFactorChallenge');
    });
}

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // AUTH Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('approvalstatus', ApprovalStatusController::class);
    Route::resource('businessstate', BusinessStateController::class);
    Route::resource('commercialstatus', CommercialStatusController::class);
    Route::resource('cities', CityController::class);
    Route::resource('countries', CountryController::class);
    Route::resource('developers', DeveloperController::class);
    Route::resource('developmentimages', DevelopmentImageController::class);
    Route::resource('development', DevelopmentController::class);
    Route::resource('developmentfile', DevelopmentFileController::class);
    Route::resource('documenttype', DocumentTypeController::class);
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
