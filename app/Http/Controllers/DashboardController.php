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
        $userRole = $user->roles->first()?->name;
        
        if ($userRole === 'Admin') {
            return $this->adminDashboard();
        } elseif ($userRole === 'DevAdmin') {
            return $this->devAdminDashboard();
        } elseif ($userRole === 'Master Pais') {
            return $this->masterPaisDashboard($user);
        } elseif ($userRole === 'Agente Inmobiliario') {
            return $this->agenteInmobiliarioDashboard($user);
        } elseif ($userRole === 'Cliente') {
            return $this->clienteDashboard($user);
        } else {
            return $this->userDashboard($user);
        }
    }

    /**
     * Dashboard para Administrador
     */
    private function adminDashboard()
    {
        // Estadísticas generales (excluyendo DevAdmin)
        $stats = [
            'totalUsers' => User::whereDoesntHave('roles', function($query) {
                $query->where('name', 'DevAdmin');
            })->count(),
            'activeDevelopers' => Developer::count(),
            'totalLeads' => Lead::count(),
            'totalDevelopments' => Development::count(),
        ];

        // Leads por Fuente
        $leadsBySource = Lead::selectRaw('leadSou_id, COUNT(*) as total')
            ->groupBy('leadSou_id')
            ->with('source:leadSou_id,leadSou_name')
            ->get()
            ->map(function ($lead) {
                return [
                    'leadSou_name' => $lead->source?->leadSou_name ?? 'Sin fuente',
                    'total' => $lead->total
                ];
            });

        // Usuarios por Perfil (excluyendo DevAdmin)
        $usersByProfile = User::with('roles')
            ->get()
            ->filter(function($user) {
                $role = $user->roles->first()?->name;
                return $role !== 'DevAdmin';
            })
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

        // Top 10 usuarios por captaciones de desarrollos (excluyendo DevAdmin)
        $topUsersByDevelopments = User::select('users.id', 'users.name', 'users.lastname', 'users.usr_id_ctry')
            ->selectRaw('COUNT(DISTINCT developments.devt_id) as development_count')
            ->leftJoin('developers', 'users.id', '=', 'developers.user_id')
            ->leftJoin('developments', 'developers.devr_id', '=', 'developments.devr_id')
            ->with('country:ctry_id,ctry_name')
            ->with('roles:id,name')
            ->groupBy('users.id', 'users.name', 'users.lastname', 'users.usr_id_ctry')
            ->orderByDesc('development_count')
            ->limit(10)
            ->get()
            ->filter(function($user) {
                $role = $user->roles->first()?->name;
                return $role !== 'DevAdmin';
            })
            ->values()
            ->map(function($user) {
                return [
                    'name' => $user->name . ' ' . $user->lastname,
                    'country' => $user->country?->ctry_name ?? 'Sin país',
                    'development_count' => $user->development_count
                ];
            });

        // Usuarios por País (excluyendo DevAdmin)
        $usersByCountry = User::with('roles', 'country:ctry_id,ctry_name')
            ->where('usr_id_ctry', '!=', null)
            ->get()
            ->filter(function($user) {
                $role = $user->roles->first()?->name;
                return $role !== 'DevAdmin';
            })
            ->values()
            ->groupBy('usr_id_ctry')
            ->map(function($group) {
                $firstUser = $group->first();
                return [
                    'ctry_name' => $firstUser->country?->ctry_name ?? 'Sin país',
                    'total' => $group->count()
                ];
            })
            ->values();

        // Leads por Estado
        $leadsByStatus = Lead::selectRaw('leadSta_id, COUNT(*) as total')
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
     * Dashboard para DevAdmin
     */
    private function devAdminDashboard()
    {
        // Estadísticas del sistema
        $stats = [
            'totalUsers' => User::count(),
            'activeUsers' => User::count(),
            'totalRoles' => 5,
            'totalPermissions' => 50,
            'totalDevelopments' => Development::count(),
            'totalLeads' => Lead::count(),
            'totalDevelopers' => Developer::count(),
            'totalCountries' => Country::count(),
        ];

        // 1. Distribución de Usuarios por Rol
        $usersByRole = User::selectRaw('COUNT(*) as total')
            ->select('id')
            ->with('roles:id,name')
            ->get()
            ->groupBy(function($user) {
                return $user->roles->first()?->name ?? 'Sin rol';
            })
            ->map(function($group, $role) {
                return [
                    'role_name' => $role,
                    'total' => $group->count()
                ];
            })
            ->values();

        // 3. Usuarios Conectados (usuarios marcados como activos)
        $activeUsersNow = User::where('usr_active', true)->count();

        // 5. Estadísticas de Base de Datos
        $databaseStats = [
            'totalUsers' => User::count(),
            'totalDevelopments' => Development::count(),
            'totalLeads' => Lead::count(),
            'totalDevelopers' => Developer::count(),
            'totalCountries' => Country::count(),
        ];

        // 7. Alertas del Sistema
        $systemAlerts = [];
        
        // Alerta: Usuarios sin rol
        $usersWithoutRole = User::whereDoesntHave('roles')->count();
        if ($usersWithoutRole > 0) {
            $systemAlerts[] = [
                'type' => 'warning',
                'message' => "$usersWithoutRole usuario(s) sin rol asignado",
                'icon' => '⚠️'
            ];
        }

        // Alerta: Developments sin developer
        $developmentsWithoutDeveloper = Development::whereNull('devr_id')->count();
        if ($developmentsWithoutDeveloper > 0) {
            $systemAlerts[] = [
                'type' => 'warning',
                'message' => "$developmentsWithoutDeveloper proyecto(s) sin desarrollador",
                'icon' => '🏗️'
            ];
        }

        // Alerta: Leads sin estado
        $leadsWithoutStatus = Lead::whereNull('leadSta_id')->count();
        if ($leadsWithoutStatus > 0) {
            $systemAlerts[] = [
                'type' => 'info',
                'message' => "$leadsWithoutStatus lead(s) sin estado definido",
                'icon' => '📋'
            ];
        }

        // Alerta: Sistema en óptimas condiciones
        if (empty($systemAlerts)) {
            $systemAlerts[] = [
                'type' => 'success',
                'message' => 'Sistema funcionando sin problemas',
                'icon' => '✅'
            ];
        }

        return Inertia::render('Dashboard', [
            'isAdmin' => false,
            'stats' => $stats,
            'usersByRole' => $usersByRole,
            'activeUsersNow' => $activeUsersNow,
            'databaseStats' => $databaseStats,
            'systemAlerts' => $systemAlerts,
        ]);
    }

    /**
     * Dashboard para Master País
     */
    private function masterPaisDashboard(User $user)
    {
        // Obtener el país del Master País
        $countryId = $user->usr_id_ctry;
        
        // Estadísticas a nivel país
        $countryLeads = Lead::whereHas('country', function($query) use ($countryId) {
            $query->where('ctry_id', $countryId);
        });
        
        $countryDevelopments = Development::where('ctry_id', $countryId);
        $countryAgents = User::whereHas('roles', function($query) {
            $query->where('name', 'Agente Inmobiliario');
        })->whereHas('country', function($query) use ($countryId) {
            $query->where('ctry_id', $countryId);
        });
        
        $convertedLeads = (clone $countryLeads)
            ->whereHas('status', function($query) {
                $query->where('leadSta_name', 'Convertido');
            })
            ->count();
        
        $stats = [
            'totalLeads' => $countryLeads->count(),
            'totalDevelopments' => $countryDevelopments->count(),
            'convertedLeads' => $convertedLeads,
            'activeAgents' => $countryAgents->where('usr_active', true)->count(),
        ];

        // 1. Top Agentes Inmobiliarios por leads generados
        $topAgents = User::whereHas('roles', function($query) {
            $query->where('name', 'Agente Inmobiliario');
        })
            ->whereHas('country', function($query) use ($countryId) {
                $query->where('ctry_id', $countryId);
            })
            ->get()
            ->map(function($agent) {
                $agentLeads = Lead::where('user_id', $agent->id)->count();
                $convertedAgentLeads = Lead::where('user_id', $agent->id)
                    ->whereHas('status', function($query) {
                        $query->where('leadSta_name', 'Convertido');
                    })
                    ->count();
                
                return [
                    'name' => $agent->name,
                    'total_leads' => $agentLeads,
                    'converted_leads' => $convertedAgentLeads,
                    'conversion_rate' => $agentLeads > 0 ? round(($convertedAgentLeads / $agentLeads) * 100, 1) : 0
                ];
            })
            ->sortByDesc('total_leads')
            ->take(5)
            ->values();

        // 2. Proyectos Más Activos en el país
        $topProjects = Development::where('developments.ctry_id', $countryId)
            ->selectRaw('developments.devt_id, developments.devt_title, COUNT(leads.lead_id) as lead_count')
            ->leftJoin('leads', 'developments.devt_id', '=', 'leads.devt_id')
            ->groupBy('developments.devt_id', 'developments.devt_title')
            ->orderByDesc('lead_count')
            ->limit(5)
            ->get()
            ->map(function($dev) {
                return [
                    'name' => $dev->devt_title,
                    'lead_count' => $dev->lead_count ?? 0
                ];
            });

        // 3. Distribución por Ciudades
        $leadsByCity = Lead::selectRaw('developments.city_id, COUNT(leads.lead_id) as total')
            ->join('developments', 'leads.devt_id', '=', 'developments.devt_id')
            ->where('developments.ctry_id', $countryId)
            ->groupBy('developments.city_id')
            ->get();

        // Cargar los nombres de las ciudades
        $leadsByCity = $leadsByCity->map(function($item) {
            $city = City::find($item->city_id);
            return [
                'city_name' => $city?->city_name ?? 'Sin ciudad',
                'total' => $item->total
            ];
        })->sortByDesc('total')->values();

        // 4. Leads por Fuente
        $leadsBySource = Lead::whereHas('country', function($query) use ($countryId) {
            $query->where('ctry_id', $countryId);
        })
            ->selectRaw('leadSou_id, COUNT(*) as total')
            ->groupBy('leadSou_id')
            ->with('source:leadSou_id,leadSou_name')
            ->get()
            ->map(function($lead) {
                return [
                    'leadSou_name' => $lead->source?->leadSou_name ?? 'Sin fuente',
                    'total' => $lead->total
                ];
            });

        // 7. Actividad Reciente - Últimos 10 leads del país
        $recentActivity = Lead::whereHas('country', function($query) use ($countryId) {
            $query->where('ctry_id', $countryId);
        })
            ->orderByDesc('created_at')
            ->limit(10)
            ->with('status:leadSta_id,leadSta_name', 'source:leadSou_id,leadSou_name', 'user:id,name')
            ->get()
            ->map(function($lead) {
                return [
                    'id' => $lead->lead_id,
                    'name' => $lead->lead_client_name ?? 'Sin nombre',
                    'agent' => $lead->user?->name ?? 'Sin agente',
                    'status' => $lead->status?->leadSta_name ?? 'Sin estado',
                    'source' => $lead->source?->leadSou_name ?? 'Sin fuente',
                    'date' => $lead->created_at->format('d/m/Y H:i'),
                ];
            });

        // Resumen por estado de leads
        $leadsByStatus = Lead::whereHas('country', function($query) use ($countryId) {
            $query->where('ctry_id', $countryId);
        })
            ->selectRaw('leadSta_id, COUNT(*) as total')
            ->groupBy('leadSta_id')
            ->with('status:leadSta_id,leadSta_name')
            ->get()
            ->map(function($lead) {
                return [
                    'leadSta_name' => $lead->status?->leadSta_name ?? 'Sin estado',
                    'total' => $lead->total
                ];
            });

        return Inertia::render('Dashboard', [
            'isAdmin' => false,
            'stats' => $stats,
            'topAgents' => $topAgents,
            'topProjects' => $topProjects,
            'leadsByCity' => $leadsByCity,
            'leadsBySource' => $leadsBySource,
            'recentActivity' => $recentActivity,
            'leadsByStatus' => $leadsByStatus,
        ]);
    }

    /**
     * Dashboard para Agente Inmobiliario
     */
    private function agenteInmobiliarioDashboard(User $user)
    {
        $userDevelopersCount = Developer::where('user_id', $user->id)->count();
        $userDevelopmentsCount = Development::where('user_id', $user->id)->count();
        $userLeadsCount = Lead::where('user_id', $user->id)->count();
        $convertedLeadsCount = Lead::where('user_id', $user->id)
            ->whereHas('status', function($query) {
                $query->where('leadSta_name', 'Convertido');
            })
            ->count();

        // Tasa de conversión
        $conversionRate = $userLeadsCount > 0 ? round(($convertedLeadsCount / $userLeadsCount) * 100, 1) : 0;

        $stats = [
            'totalLeads' => $userLeadsCount,
            'totalDevelopments' => $userDevelopmentsCount,
            'activeLeads' => $convertedLeadsCount,
            'conversionRate' => $conversionRate,
        ];

        // 2. Actividad Reciente - Últimos 10 leads
        $recentActivity = Lead::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(10)
            ->with('status:leadSta_id,leadSta_name', 'source:leadSou_id,leadSou_name')
            ->get()
            ->map(function($lead) {
                return [
                    'id' => $lead->lead_id,
                    'name' => $lead->lead_client_name ?? 'Sin nombre',
                    'status' => $lead->status?->leadSta_name ?? 'Sin estado',
                    'source' => $lead->source?->leadSou_name ?? 'Sin fuente',
                    'date' => $lead->created_at->format('d/m/Y H:i'),
                ];
            });

        // 3. Leads por Fuente
        $leadsBySource = Lead::where('user_id', $user->id)
            ->selectRaw('leadSou_id, COUNT(*) as total')
            ->groupBy('leadSou_id')
            ->with('source:leadSou_id,leadSou_name')
            ->get()
            ->map(function($lead) {
                return [
                    'leadSou_name' => $lead->source?->leadSou_name ?? 'Sin fuente',
                    'total' => $lead->total
                ];
            });

        // 4. Top 5 Proyectos por Leads
        $topProjectsByLeads = Development::where('developments.user_id', $user->id)
            ->selectRaw('developments.devt_id, developments.devt_title, COUNT(leads.lead_id) as lead_count')
            ->leftJoin('leads', 'developments.devt_id', '=', 'leads.devt_id')
            ->groupBy('developments.devt_id', 'developments.devt_title')
            ->orderByDesc('lead_count')
            ->limit(5)
            ->get()
            ->map(function($dev) {
                return [
                    'name' => $dev->devt_title,
                    'lead_count' => $dev->lead_count ?? 0
                ];
            });

        // 5. Desempeño del Período (Este mes vs mes anterior)
        $thisMonth = Lead::where('user_id', $user->id)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->count();
        
        $lastMonth = Lead::where('user_id', $user->id)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m') - 1)
            ->count();

        $monthlyComparison = [
            'thisMonth' => $thisMonth,
            'lastMonth' => $lastMonth,
            'growth' => $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : 0
        ];

        // 8. Resumen Rápido - Leads por estado
        $quickSummary = Lead::where('user_id', $user->id)
            ->selectRaw('leadSta_id, COUNT(*) as total')
            ->groupBy('leadSta_id')
            ->with('status:leadSta_id,leadSta_name')
            ->get()
            ->map(function($lead) {
                return [
                    'status_name' => $lead->status?->leadSta_name ?? 'Sin estado',
                    'total' => $lead->total
                ];
            });

        // Leads por Estado para gráfico
        $userLeadsByStatus = Lead::where('user_id', $user->id)
            ->selectRaw('leadSta_id, COUNT(*) as total')
            ->groupBy('leadSta_id')
            ->with('status:leadSta_id,leadSta_name')
            ->get()
            ->map(function($lead) {
                return [
                    'leadSta_name' => $lead->status?->leadSta_name ?? 'Sin estado',
                    'total' => $lead->total
                ];
            });

        return Inertia::render('Dashboard', [
            'isAdmin' => false,
            'stats' => $stats,
            'recentActivity' => $recentActivity,
            'leadsBySource' => $leadsBySource,
            'topProjectsByLeads' => $topProjectsByLeads,
            'monthlyComparison' => $monthlyComparison,
            'quickSummary' => $quickSummary,
            'userLeadsByStatus' => $userLeadsByStatus,
        ]);
    }

    /**
     * Dashboard para Cliente
     */
    private function clienteDashboard(User $user)
    {
        $userLeadsCount = Lead::where('user_id', $user->id)->count();
        $userDevelopmentsCount = Development::count();

        $stats = [
            'totalLeads' => $userLeadsCount,
            'totalDevelopments' => $userDevelopmentsCount,
        ];

        return Inertia::render('Dashboard', [
            'isAdmin' => false,
            'stats' => $stats,
            'userLeadsByStatus' => [],
        ]);
    }

    /**
     * Dashboard por defecto
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
