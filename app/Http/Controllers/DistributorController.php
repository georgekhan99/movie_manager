<?php

namespace App\Http\Controllers;

use App\Models\Cinemas;
use App\Models\Company;
use App\Models\Duration;
use App\Models\movies;
use App\Models\Placement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class DistributorController extends Controller
{
    public function getMoviePageLoad()
    {
        $Company_list = Company::all(['id', 'company_legalname']);
        return Inertia::render('DistributorDashboard/addMovie', ['company_list' => $Company_list]);
    }

    public function addMovie(Request $request)
    {
        try {
            $data = array();
            $data['movies_name'] = $request->Movie_name;
            $data['company_id'] = $request->Company_name;
            $data['movies_release_date'] = $request->Release_date;
            movies::create($data);
            return redirect()->route('distributor.movie.all')->with('success', 'Movie Saved Successfully');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create movie',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function EditMovie(Request $request){
        try{
           $id = $request->id;
           $movie = movies::where('id', $id)->first();
           $movie->update([
            'movies_name' => $request->movies_name,
            'company_id' => $request->company_id,
            'movies_release_date' => $request->movies_release_date,
           ]);
           return redirect()->back()->with(['Success']);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'failed To Create Movie',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function DeleteMovie($id){
        try{
            $movie = movies::findOrFail($id);
            $movie->delete();
            return redirect()->back()->with(['success']);
        }catch(\Exception $e){
            return response()->json([
            'message' => 'Failed to delete movie',
            'error' => $e->getMessage(), 
            ]);
        }
    }
    public function getMovieTablePageLoad()
    {
        $movie_list = movies::all();
        $company_list = Company::all(['id', 'company_legalname']);
        return Inertia::render(
            'DistributorDashboard/MovieTable',
            [
                'movie_list' => $movie_list,
                'company_list' => $company_list
            ]
        );
    }
#region ExistingCode to  get booking calendar
    // public function BookingCalendar($id) {
    //     // Get Movie Details
    //     $MovieReleaseDate = movies::findOrFail($id, ['id', 'movies_name', 'movies_release_date']);
    
    //     // Get the Current Duration
    //     $releaseDate = Duration::whereRaw('? BETWEEN start_date AND DATE_ADD(start_date, INTERVAL 14 DAY)', 
    //         [$MovieReleaseDate->movies_release_date])
    //         ->first(['id', 'start_date']);
    
    //     if (!$releaseDate) {
    //         return response()->json(['error' => 'No matching duration found'], 404);
    //     }
    
    //     // Fetch Placement List (Ensure `placement_id` is included)
    //     $placementList = DB::table('cinema_placements')
    //         ->join('cinemas', 'cinemas.id', '=', 'cinema_placements.cinema_id')
    //         ->select('cinemas.cinema_name', 'cinema_placements.id as placement_id', 'cinema_placements.placement_name')
    //         ->get()
    //         ->groupBy('cinema_name')
    //         ->map(function ($group) {
    //             return [
    //                 'cinema_name' => $group->first()->cinema_name,
    //                 'placements' => $group->map(function ($placement) {
    //                     return [
    //                         'placement_id' => $placement->placement_id,
    //                         'placement_name' => $placement->placement_name
    //                     ];
    //                 })->toArray()
    //             ];
    //         })->values();
    
    //     // Fetch Previous 3 Durations
    //     $previousDurations = Duration::where('start_date', '<', $releaseDate->start_date)
    //         ->orderBy('start_date', 'desc')
    //         ->limit(3)
    //         ->get(['id', 'start_date']);
    //     $previousDurations = $previousDurations->reverse(); // Fix order
    
    //     // Fetch Next 3 Durations
    //     $nextDurations = Duration::where('start_date', '>', $releaseDate->start_date)
    //         ->orderBy('start_date', 'asc')
    //         ->limit(3)
    //         ->get(['id', 'start_date']);
    
    //     // Merge Durations to Ensure Correct Order
    //     $durations = $previousDurations->merge([$releaseDate])->merge($nextDurations);
    
    //     return Inertia::render('DistributorDashboard/BookingCalendar', [
    //         'releaseDate' => $releaseDate,
    //         'PlacementList' => $placementList,
    //         'durations' => $durations, // Pass durations as array of objects (id + start_date)
    //         'Movie_details' => $MovieReleaseDate
    //     ]);
    // }

//     public function BookingCalendar($id)
// {
//     // Get Movie Details
//     $MovieReleaseDate = movies::findOrFail($id, ['id', 'movies_name', 'movies_release_date']);

//     // Get the Current Duration
//     $releaseDate = Duration::whereRaw('? BETWEEN start_date AND DATE_ADD(start_date, INTERVAL 14 DAY)', 
//         [$MovieReleaseDate->movies_release_date])
//         ->first(['id', 'start_date']);

//     if (!$releaseDate) {
//         return response()->json(['error' => 'No matching duration found'], 404);
//     }

//     // Fetch Placement List with Booking Status
//     $placementList = DB::table('cinema_placements')
//         ->join('cinemas', 'cinemas.id', '=', 'cinema_placements.cinema_id')
//         ->leftJoin('bookings_detail as bd', function($join) {
//             $join->on('cinema_placements.id', '=', 'bd.placement_id');
//         })
//         ->leftJoin('bookings as b', 'bd.booking_id', '=', 'b.id')
//         ->select('cinemas.cinema_name', 'cinema_placements.id as placement_id', 'cinema_placements.placement_name',
//                  DB::raw("MAX(CASE WHEN bd.booking_status = 'accepted' THEN 1 ELSE 0 END) as is_confirmed"))
//         ->groupBy('cinemas.cinema_name', 'cinema_placements.id', 'cinema_placements.placement_name')
//         ->get()
//         ->groupBy('cinema_name')
//         ->map(function ($group) {
//             return [
//                 'cinema_name' => $group->first()->cinema_name,
//                 'placements' => $group->map(function ($placement) {
//                     return [
//                         'placement_id' => $placement->placement_id,
//                         'placement_name' => $placement->placement_name,
//                         'is_confirmed' => $placement->is_confirmed
//                     ];
//                 })->toArray()
//             ];
//         })->values();

//     // Fetch Previous 3 Durations
//     $previousDurations = Duration::where('start_date', '<', $releaseDate->start_date)
//         ->orderBy('start_date', 'desc')
//         ->limit(3)
//         ->get(['id', 'start_date'])->reverse();

//     // Fetch Next 3 Durations
//     $nextDurations = Duration::where('start_date', '>', $releaseDate->start_date)
//         ->orderBy('start_date', 'asc')
//         ->limit(3)
//         ->get(['id', 'start_date']);

//     $durations = $previousDurations->merge([$releaseDate])->merge($nextDurations);

//     return Inertia::render('DistributorDashboard/BookingCalendar', [
//         'releaseDate' => $releaseDate,
//         'PlacementList' => $placementList,
//         'durations' => $durations,
//         'Movie_details' => $MovieReleaseDate
//     ]);
// }

// public function BookingCalendar($id)
// {
//     // Get Movie Details
//     $MovieReleaseDate = movies::findOrFail($id, ['id', 'movies_name', 'movies_release_date']);

//     // Get the Current Duration
//     $releaseDate = Duration::whereRaw('? BETWEEN start_date AND DATE_ADD(start_date, INTERVAL 14 DAY)', 
//         [$MovieReleaseDate->movies_release_date])
//         ->first(['id', 'start_date']);

//     if (!$releaseDate) {
//         return response()->json(['error' => 'No matching duration found'], 404);
//     }

//     $placementList = DB::table('cinema_placements as cp')
//     ->join('cinemas as c', 'c.id', '=', 'cp.cinema_id')
//     ->leftJoin('bookings_detail as bd', 'cp.id', '=', 'bd.placement_id')
//     ->leftJoin('bookings as b', 'bd.booking_id', '=', 'b.id')
//     ->leftJoin('movies as m', 'b.movie_id', '=', 'm.id')
//     ->select(
//         'c.cinema_name',
//         'cp.id as placement_id',
//         'cp.placement_name',
//         'cp.placement_width',
//         'cp.placement_height',
//         'cp.placement_price',
//         'bd.duration_id',
//         DB::raw("COALESCE(MAX(CASE WHEN bd.booking_status = 'accepted' THEN 1 ELSE 0 END), 0) as is_confirmed"),
//         DB::raw("COALESCE(MAX(CASE WHEN bd.booking_status = 'accepted' THEN m.movies_name ELSE NULL END), 'Not Confirmed') as accepted_movie")
//     )
//     ->groupBy('c.cinema_name', 'cp.id', 'cp.placement_name', 'bd.duration_id')
//     ->get()
//     ->groupBy('cinema_name')
//     ->map(function ($group) {
//         return [
//             'cinema_name' => $group->first()->cinema_name,
//             'placements' => $group->map(function ($placement) {
//                 return [
//                     'placement_id' => $placement->placement_id, 
//                     'placement_name' => $placement->placement_name,
//                     'placement_width' => $placement->placement_width,
//                     'placement_height' => $placement->placement_height,
//                     'placement_price' => $placement->placement_price,
//                     'duration_id' => $placement->duration_id ?? null, // Handle NULL durations
//                     'is_confirmed' => $placement->is_confirmed,
//                     'accepted_movie' => $placement->accepted_movie
//                 ];
//             })->toArray()
//         ];
//     })->values();

//     // dd($placementList);

//     // Fetch Previous 3 Durations
//     $previousDurations = Duration::where('start_date', '<', $releaseDate->start_date)
//         ->orderBy('start_date', 'desc')
//         ->limit(4)
//         ->get(['id', 'start_date'])->reverse();

//     // Fetch Next 3 Durations
//     $nextDurations = Duration::where('start_date', '>', $releaseDate->start_date)
//         ->orderBy('start_date', 'asc')
//         ->limit(3)
//         ->get(['id', 'start_date']);

//     $durations = $previousDurations->merge([$releaseDate])->merge($nextDurations);

    
    
    

//     return Inertia::render('DistributorDashboard/BookingCalendar', [
//         'releaseDate' => $releaseDate,
//         'PlacementList' => $placementList,
//         'durations' => $durations,
//         'Movie_details' => $MovieReleaseDate
//     ]);
// }
#endregion
public function BookingCalendar($id)
{
    // Get authenticated user
    $user = auth()->user();
    $userCompanyId = $user->user_company_id ?? null; // ✅ Correct user company ID

    // Get Movie Details
    $MovieReleaseDate = movies::findOrFail($id, ['id', 'movies_name', 'movies_release_date', 'company_id']);

    // Get the Current Duration
    $releaseDate = Duration::whereRaw('? BETWEEN start_date AND DATE_ADD(start_date, INTERVAL 14 DAY)', 
        [$MovieReleaseDate->movies_release_date])
        ->first(['id', 'start_date']);

    if (!$releaseDate) {
        return response()->json(['error' => 'No matching duration found'], 404);
    }

    $placementList = DB::table('cinema_placements as cp')
    ->join('cinemas as c', 'c.id', '=', 'cp.cinema_id')
    ->leftJoin('bookings_detail as bd', 'cp.id', '=', 'bd.placement_id')
    ->leftJoin('bookings as b', 'bd.booking_id', '=', 'b.id')
    ->leftJoin('movies as m', 'b.movie_id', '=', 'm.id')
    ->select(
        'c.cinema_name',
        'cp.id as placement_id',
        'cp.placement_name',
        'cp.placement_width',
        'cp.placement_height',
        'cp.placement_price',
        'bd.duration_id',
        DB::raw("COALESCE(MAX(CASE WHEN bd.booking_status = 'accepted' THEN 1 ELSE 0 END), 0) as is_confirmed"),
        DB::raw("
            CASE 
                WHEN MAX(CASE WHEN bd.booking_status = 'accepted' THEN m.company_id ELSE NULL END) = " . (int)$userCompanyId . " 
                THEN MAX(CASE WHEN bd.booking_status = 'accepted' THEN m.movies_name ELSE NULL END) 
                ELSE 'N/A' 
            END AS accepted_movie
        ")
    )
    ->groupBy('c.cinema_name', 'cp.id', 'cp.placement_name', 'bd.duration_id')
    ->get()
    ->groupBy('cinema_name')
    ->map(function ($group) {
        return [
            'cinema_name' => $group->first()->cinema_name,
            'placements' => $group->map(function ($placement) {
                return [
                    'placement_id' => $placement->placement_id, 
                    'placement_name' => $placement->placement_name,
                    'placement_width' => $placement->placement_width,
                    'placement_height' => $placement->placement_height,
                    'placement_price' => $placement->placement_price,
                    'duration_id' => $placement->duration_id ?? null, // Handle NULL durations
                    'is_confirmed' => $placement->is_confirmed,
                    'accepted_movie' => $placement->accepted_movie // "N/A" if not same company
                ];
            })->toArray()
        ];
    })->values();

    // Fetch Previous 3 Durations
    $previousDurations = Duration::where('start_date', '<', $releaseDate->start_date)
        ->orderBy('start_date', 'desc')
        ->limit(4)
        ->get(['id', 'start_date', 'production_deadline'])->reverse();

    // Fetch Next 3 Durations
    $nextDurations = Duration::where('start_date', '>', $releaseDate->start_date)
        ->orderBy('start_date', 'asc')
        ->limit(3)
        ->get(['id', 'start_date', 'production_deadline']);

    $durations = $previousDurations->merge([$releaseDate])->merge($nextDurations);

    $userMovies = movies::where('company_id', $userCompanyId)->get(['id', 'movies_name']);

    



    return Inertia::render('DistributorDashboard/BookingCalendar', [
        'releaseDate' => $releaseDate,
        'PlacementList' => $placementList,
        'durations' => $durations,
        'Movie_details' => $MovieReleaseDate,
        'userMovie' =>  $userMovies
    ]);
}



    

    public function getEditMoviePage($id)
    {
        $id = null;
        return Inertia::render('DistributorDashboard/editMoviePage');
    }

    public function editPlacement($id)
    {
        $id = null;
        return Inertia::render('DistributorDashboard/editPlacement');
    }

    public function showCalendar()
    {
        
        return Inertia::render('DistributorDashboard/PlacementCalendar');
    }
}
