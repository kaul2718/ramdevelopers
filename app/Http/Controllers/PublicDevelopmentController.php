<?php
namespace App\Http\Controllers;

use App\Models\Development;
use App\Models\Developer;
use App\Models\Country;
use App\Models\City;
use App\Models\HousingType;
use App\Models\DevelopmentFile;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Storage;

class PublicDevelopmentController extends Controller
{
    /**
     * Get featured developments for landing page
     */
    public function featured()
    {
        $developments = Development::where('devt_is_featured', true)
            ->with(['country', 'city', 'housingType', 'images'])
            ->latest()
            ->limit(3)
            ->get()
            ->map(function ($development) {
                // Get first image and construct full URL
                $image = $development->images->first();
                $imageUrl = null;
                if ($image && $image->devImg_url) {
                    // If URL starts with http, use it as is
                    if (str_starts_with($image->devImg_url, 'http')) {
                        $imageUrl = $image->devImg_url;
                    } else {
                        // Construct full URL to public storage
                        $imageUrl = asset('storage/' . $image->devImg_url);
                    }
                }
                
                return [
                    'devt_id' => $development->devt_id,
                    'devt_slug' => $development->devt_slug,
                    'devt_title' => $development->devt_title,
                    'devt_price_from' => $development->devt_price_from,
                    'devt_price_to' => $development->devt_price_to,
                    'country' => $development->country,
                    'city' => $development->city,
                    'housingType' => $development->housingType,
                    'status' => 'Disponible',
                    'image' => $imageUrl,
                ];
            });

        return response()->json($developments);
    }

    /**
     * Get featured developers for landing page avatars
     */
    public function featuredDevelopers()
    {
        $developers = Developer::where('devr_active', true)
            ->with(['user', 'country'])
            ->withCount('developments')
            ->orderByDesc('developments_count')
            ->limit(3)
            ->get()
            ->map(function ($developer) {
                // Construct profile photo URL if exists
                $profilePhoto = null;
                if ($developer->user && $developer->user->profile_photo_path) {
                    if (str_starts_with($developer->user->profile_photo_path, 'http')) {
                        $profilePhoto = $developer->user->profile_photo_path;
                    } else {
                        $profilePhoto = asset('storage/' . $developer->user->profile_photo_path);
                    }
                }

                return [
                    'devr_id' => $developer->devr_id,
                    'devr_commercial_name' => $developer->devr_commercial_name,
                    'profile_photo' => $profilePhoto,
                    'developments_count' => $developer->developments_count,
                    'country' => $developer->country
                ];
            });

        return response()->json($developers);
    }

    /**
     * Get all developments for public listing page
     */
    public function listing(Request $request)
    {
        $query = Development::where('devt_active', true)
            ->with(['country', 'city', 'housingType', 'images', 'businessStatus', 'commercialStatus'])
            ->latest();

        // Search filter
        $search = $request->get('search', '');
        if ($search) {
            $words = collect(array_filter(explode(' ', trim($search))));
            if ($words->isNotEmpty()) {
                $query->where(function ($q) use ($words) {
                    foreach ($words as $word) {
                        $q->where(function ($subQ) use ($word) {
                            $subQ->whereRaw('LOWER(devt_title) LIKE ?', ["%{$word}%"])
                                  ->orWhereRaw('LOWER(devt_short_description) LIKE ?', ["%{$word}%"])
                                  ->orWhereHas('city', function ($c) use ($word) {
                                      $c->whereRaw('LOWER(city_name) LIKE ?', ["%{$word}%"]);
                                  })
                                  ->orWhereHas('country', function ($c) use ($word) {
                                      $c->whereRaw('LOWER(ctry_name) LIKE ?', ["%{$word}%"]);
                                  });
                        });
                    }
                });
            }
        }

        // Country filter
        if ($request->has('country') && $request->get('country')) {
            $query->where('ctry_id', $request->get('country'));
        }

        // City filter
        if ($request->has('city') && $request->get('city')) {
            $query->where('city_id', $request->get('city'));
        }

        // Housing type filter
        if ($request->has('housing_type') && $request->get('housing_type')) {
            $query->where('houTyp_id', $request->get('housing_type'));
        }

        // Price range filter
        if ($request->has('price_from') && $request->get('price_from')) {
            $query->where('devt_price_from', '>=', $request->get('price_from'));
        }
        if ($request->has('price_to') && $request->get('price_to')) {
            $query->where('devt_price_to', '<=', $request->get('price_to'));
        }

        $developments = $query->paginate(12)->appends($request->query());

        // Format developments for frontend
        $formattedDevelopments = $developments->map(function ($dev) {
            $image = $dev->images->first();
            $imageUrl = null;
            if ($image && $image->devImg_url) {
                if (str_starts_with($image->devImg_url, 'http')) {
                    $imageUrl = $image->devImg_url;
                } else {
                    $imageUrl = asset('storage/' . $image->devImg_url);
                }
            }

            return [
                'devt_id' => $dev->devt_id,
                'devt_slug' => $dev->devt_slug,
                'devt_title' => $dev->devt_title,
                'devt_short_description' => $dev->devt_short_description,
                'devt_price_from' => $dev->devt_price_from,
                'devt_price_to' => $dev->devt_price_to,
                'devt_estimated_profit' => $dev->devt_estimated_profit,
                'devt_estimated_profit_is_percentage' => $dev->devt_estimated_profit_is_percentage,
                'image' => $imageUrl,
                'city' => $dev->city,
                'country' => $dev->country,
                'housingType' => $dev->housingType,
                'businessStatus' => $dev->businessStatus,
                'commercialStatus' => $dev->commercialStatus,
            ];
        });

        // Get filter options
        $countries = Country::where('ctry_active', true)->orderBy('ctry_name')->get();
        $cities = City::where('city_active', true)->orderBy('city_name')->get();
        $housingTypes = HousingType::where('houTyp_active', true)->orderBy('houTyp_name')->get();

        return Inertia::render('Developments', [
            'developments' => $formattedDevelopments,
            'pagination' => [
                'current_page' => $developments->currentPage(),
                'per_page' => $developments->perPage(),
                'total' => $developments->total(),
                'last_page' => $developments->lastPage(),
            ],
            'countries' => $countries,
            'cities' => $cities,
            'housingTypes' => $housingTypes,
            'filters' => [
                'search' => $search,
                'country' => $request->get('country'),
                'city' => $request->get('city'),
                'housing_type' => $request->get('housing_type'),
                'price_from' => $request->get('price_from'),
                'price_to' => $request->get('price_to'),
            ]
        ]);
    }

    /**
     * Show a single development publicly
     */
    public function show($devt_slug)
    {
        $development = Development::where('devt_slug', $devt_slug)
            ->where('devt_active', true)
            ->with(['country', 'city', 'housingType', 'images', 'businessStatus', 'commercialStatus', 'developer.user', 'files.documentType', 'user'])
            ->firstOrFail();

        $images = $development->images->map(function ($img) {
            if ($img->devImg_url && !str_starts_with($img->devImg_url, 'http')) {
                $img->devImg_url = asset('storage/' . $img->devImg_url);
            }
            return $img;
        });

        return Inertia::render('DevelopmentDetail', [
            'development' => [
                'devt_id' => $development->devt_id,
                'devt_slug' => $development->devt_slug,
                'devt_title' => $development->devt_title,
                'devt_short_description' => $development->devt_short_description,
                'devt_long_description' => $development->devt_long_description,
                'devt_address' => $development->devt_address,
                'devt_price_from' => $development->devt_price_from,
                'devt_price_to' => $development->devt_price_to,
                'devt_estimated_profit' => $development->devt_estimated_profit,
                'devt_estimated_profit_is_percentage' => $development->devt_estimated_profit_is_percentage,
                'devt_delivery_year' => $development->devt_delivery_year,
                'devt_storage_rooms' => $development->devt_storage_rooms,
                'devt_parking_spaces' => $development->devt_parking_spaces,
                'devt_terraces' => $development->devt_terraces,
                'devt_swimming_pools' => $development->devt_swimming_pools,
                'devt_children_areas' => $development->devt_children_areas,
                'devt_green_zones' => $development->devt_green_zones,
                'devt_elevators' => $development->devt_elevators,
                'devt_golf_courses' => $development->devt_golf_courses,
                'devt_bedrooms' => $development->devt_bedrooms,
                'images' => $images,
                'city' => $development->city,
                'country' => $development->country,
                'housingType' => $development->housingType,
                'businessStatus' => $development->businessStatus,
                'commercialStatus' => $development->commercialStatus,
                'developer' => $development->developer ? [
                    'devr_id' => $development->developer->devr_id,
                    'devr_commercial_name' => $development->developer->devr_commercial_name,
                    'dev_name' => $development->developer->devr_commercial_name,
                    'devr_email_contact' => $development->developer->devr_email_contact,
                    'devr_phone_contact' => $development->developer->devr_phone_contact,
                    'devr_website' => $development->developer->devr_website,
                    'user' => $development->developer->user ? [
                        'id' => $development->developer->user->id,
                        'name' => $development->developer->user->name,
                        'email' => $development->developer->user->email,
                        'profile_photo_path' => $development->developer->user->profile_photo_path,
                    ] : null,
                ] : null,
                'files' => $development->files->map(function ($file) {
                    $fileUrl = $file->devFile_url;
                    if ($fileUrl && !str_starts_with($fileUrl, 'http')) {
                        $fileUrl = asset('storage/' . $fileUrl);
                    }
                    return [
                        'devFile_id' => $file->devFile_id,
                        'devFile_name' => $file->devFile_name,
                        'devFile_url' => $fileUrl,
                        'devFile_isPublic' => $file->devFile_isPublic,
                        'documentType' => $file->documentType,
                    ];
                })->all(),
            ]
        ]);
    }

    /**
     * Download a development file
     */
    public function downloadFile($devt_slug, $file_id)
    {
        // Verificar que el desarrollo es público y activo
        $development = Development::where('devt_slug', $devt_slug)
            ->where('devt_active', true)
            ->firstOrFail();

        // Verificar que el archivo existe y pertenece al desarrollo
        $file = DevelopmentFile::where('devFile_id', $file_id)
            ->where('devt_id', $development->devt_id)
            ->where('devFile_isPublic', true)
            ->firstOrFail();

        // Retornar la URL del archivo para que el navegador lo descargue
        return response()->redirectTo(asset('storage/' . $file->devFile_url));
    }
}
