<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Developer;
use App\Models\Lead;
use App\Models\Development;
use App\Models\Country;
use App\Models\City;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(){
        // Obtener estadísticas de las tablas principales
        $stats = [
            'totalUsers' => User::count(),
            'activeDevelopers' => Developer::count(),
            'totalLeads' => Lead::count(),
            'totalDevelopments' => Development::count(),
            'totalCountries' => Country::count(),
            'totalCities' => City::count(),
        ];

        // Obtener datos para gráficos
        $usersByCountry = User::select('usr_id_ctry')
            ->selectRaw('count(*) as total')
            ->where('usr_id_ctry', '!=', null)
            ->groupBy('usr_id_ctry')
            ->with('country:ctry_id,ctry_name')
            ->get()
            ->map(function ($user) {
                return [
                    'ctry_name' => $user->country?->ctry_name ?? 'Sin país',
                    'total' => $user->total
                ];
            });

        $leadsByStatus = Lead::select('leadSta_id')
            ->selectRaw('count(*) as total')
            ->groupBy('leadSta_id')
            ->with('status:leadSta_id,leadSta_name')
            ->get()
            ->map(function ($lead) {
                return [
                    'leadSta_name' => $lead->status?->leadSta_name ?? 'Sin estado',
                    'total' => $lead->total
                ];
            });

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'usersByCountry' => $usersByCountry,
            'leadsByStatus' => $leadsByStatus,
        ]);
    }
}
