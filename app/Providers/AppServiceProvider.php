<?php

namespace App\Providers;

use App\Models\Country;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share([
            'countries' => fn () => Country::where('ctry_active', true)->orderBy('ctry_name')->get(),
        ]);
    }
}
