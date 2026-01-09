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
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        $user = Auth::user();
        $isAdmin = $user->hasRole('admin');
        
        if ($isAdmin) {
            return $this->adminDashboard();
        } else {
            return $this->userDashboard($user);
        }
    }

    /**
     * Dashboard para Administrador
     */
    private function adminDashboard()
    {
        // Estadísticas generales
        $stats = [
            'totalUsers' => User::count(),
            'activeDevelopers' => Developer::count(),
            'totalLeads' => Lead::count(),
            'totalDevelopments' => Development::count(),
        ];

        // Leads por Fuente
        $leadsBySource = Lead::select('leadSou_id')
            ->selectRaw('count(*) as total')
            ->groupBy('leadSou_id')
            ->with('source:leadSou_id,leadSou_name')
            ->get()
            ->map(function ($lead) {
                return [
                    'leadSou_name' => $lead->source?->leadSou_name ?? 'Sin fuente',
                    'total' => $lead->total
                ];
            });

        // Usuarios por Perfil (usando roles)
        $usersByProfile = User::selectRaw('COUNT(*) as total')
            ->select('id')
            ->with('roles:id,name')
            ->get()
            ->groupBy(function($user) {
                return $user->roles->first()?->name ?? 'Sin perfil';
            })
            ->map(function($group, $role) {
                return [
                    'role_name' => $role,
                    'total' => $group->count()
                ];
            })
            ->values();

        // Top 10 usuarios por captaciones de desarrollos
        $topUsersByDevelopments = User::select('users.id', 'users.name', 'users.lastname', 'users.usr_id_ctry')
            ->selectRaw('COUNT(DISTINCT developments.devt_id) as development_count')
            ->leftJoin('developers', 'users.id', '=', 'developers.user_id')
            ->leftJoin('developments', 'developers.devr_id', '=', 'developments.devr_id')
            ->with('country:ctry_id,ctry_name')
            ->groupBy('users.id', 'users.name', 'users.lastname', 'users.usr_id_ctry')
            ->orderByDesc('development_count')
            ->limit(10)
            ->get()
            ->map(function($user) {
                return [
                    'name' => $user->name . ' ' . $user->lastname,
                    'country' => $user->country?->ctry_name ?? 'Sin país',
                    'development_count' => $user->development_count
                ];
            });

        // Usuarios por País (para gráfico)
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

        // Leads por Estado
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
            'isAdmin' => true,
            'stats' => $stats,
            'leadsBySource' => $leadsBySource,
            'usersByProfile' => $usersByProfile,
            'topUsersByDevelopments' => $topUsersByDevelopments,
            'usersByCountry' => $usersByCountry,
            'leadsByStatus' => $leadsByStatus,
        ]);
    }

    /**
     * Dashboard para Usuario regular
     */
    private function userDashboard(User $user)
    {
        // Estadísticas del usuario
        $userDevelopersCount = Developer::where('user_id', $user->id)->count();
        
        $userDevelopmentsCount = Development::whereHas('developer', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        $stats = [
            'totalDevelopers' => Developer::count(),
            'totalDevelopments' => Development::count(),
            'userDevelopers' => $userDevelopersCount,
            'userDevelopments' => $userDevelopmentsCount,
        ];

        // Leads del usuario por estado
        $userLeadsByStatus = Lead::select('leadSta_id')
            ->selectRaw('count(*) as total')
            ->where('user_id', $user->id)
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
            'isAdmin' => false,
            'stats' => $stats,
            'userLeadsByStatus' => $userLeadsByStatus,
        ]);
    }
}
