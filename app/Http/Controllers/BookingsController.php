<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Duration;
use App\Models\Bookings;
use App\Models\Booking_placement;
use App\Models\Booking_duration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\bookings_detail;
use GrahamCampbell\ResultType\Success;

class BookingsController extends Controller
{
    public function getCalendarPageload()
    {

        return Inertia::render('AdminDashboard/BookingCalendar/CalendarPage');
    }

    public function showDuration()
    {
        //ประกาศวันที่เริ่มต้น;
        $duration = Duration::all();
        return Inertia::render(
            'AdminDashboard/BookingCalendar/Duration',
            [
                'duration' => $duration
            ]
        );
    }

    public function getPlcementStatus($id, Request $request)
    {
        $statusFilter = $request->query('status', 'all');
    
        $placement = DB::table('cinema_placements as cp')
            ->where('cp.id', $id)
            ->first(['cp.id as placement_id', 'cp.placement_name']);
    
        if (!$placement) {
            return redirect()->route('dashboard')->with('error', 'Placement not found');
        }
    
        $durationsQuery = DB::table('durations as d')
            ->select(
                'd.id as duration_id',
                'd.start_date',
                'd.delivery_date',
                DB::raw("(SELECT GROUP_CONCAT(DISTINCT b.id) 
                          FROM bookings b
                          JOIN bookings_detail bd ON b.id = bd.booking_id
                          WHERE bd.placement_id = ? 
                          AND bd.duration_id = d.id) AS booking_id"),
    
                DB::raw("
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 
                            FROM bookings_detail bd
                            WHERE bd.placement_id = ? 
                            AND bd.duration_id = d.id
                            AND bd.booking_status = 'accepted'
                        ) THEN 'Accepted'
                        WHEN EXISTS (
                            SELECT 1 
                            FROM bookings_detail bd
                            WHERE bd.placement_id = ? 
                            AND bd.duration_id = d.id
                            AND bd.booking_status = 'pending'
                        ) THEN 'Pending'
                        ELSE 'Available'
                    END AS status
                "),
    
                // Fetch accepted movie name when status is accepted
                DB::raw("
                    (SELECT m.movies_name 
                     FROM movies m
                     JOIN bookings b ON m.id = b.movie_id
                     JOIN bookings_detail bd ON b.id = bd.booking_id
                     WHERE bd.placement_id = ? 
                     AND bd.duration_id = d.id
                     AND bd.booking_status = 'accepted'
                     LIMIT 1
                    ) AS accepted_movie
                "),
    
                // Fetch movies competing when status is not accepted
                DB::raw("
                    (SELECT JSON_ARRAYAGG(JSON_OBJECT('id', m.id, 'movie_name', m.movies_name)) 
                     FROM movies m
                     JOIN bookings b ON m.id = b.movie_id 
                     JOIN bookings_detail bd ON b.id = bd.booking_id 
                     WHERE bd.placement_id = ? 
                     AND bd.duration_id = d.id
                    ) AS movies_competing
                ")
            )
            ->setBindings([$id, $id, $id, $id, $id]);
    
        if ($statusFilter !== 'all') {
            $durationsQuery->havingRaw("status = ?", [ucfirst($statusFilter)]);
        }
    
        $durations = $durationsQuery->get()->map(function ($item) {
            if ($item->status === 'Accepted') {
                $item->accepted_movie = $item->accepted_movie;
                unset($item->movies_competing);
            } else {
                $item->movies_competing = $item->movies_competing;
                unset($item->accepted_movie);
            }
            return $item;
        });
    
        return Inertia::render(
            'MoviesManager/MovieStatusDetail',
            [
                'placement' => $placement,
                'durations' => $durations,
                'statusFilter' => $statusFilter
            ]
        );
    }
    



// public function confirmBookings(Request $request)
// {
//     $placementId = $request->input('placement_id');
//     $durationId = $request->input('duration_id');
//     $selectedMovieId = $request->input('selected_movie');

//     // Fetch competing bookings only for the specified placement and duration
//     $competingBookings = DB::table('bookings')
//         ->join('bookings_detail', function ($join) use ($placementId, $durationId) {
//             $join->on('bookings.id', '=', 'bookings_detail.booking_id')
//                 ->where('bookings_detail.placement_id', $placementId)
//                 ->where('bookings_detail.duration_id', $durationId);
//         })
//         ->select('bookings.id', 'bookings.movie_id', 'bookings_detail.id as detail_id')
//         ->get();

//     if ($competingBookings->isEmpty()) {
//         return response()->json(['error' => 'No bookings for this placement and duration'], 404);
//     }

//     // // Confirm selected booking and cancel others only for the same placement and duration
//     DB::transaction(function () use ($competingBookings, $selectedMovieId) {
//         foreach ($competingBookings as $booking) {
//             DB::table('bookings_detail')
//                 ->where('id', $booking->detail_id)
//                 ->update([
//                     'booking_status' => ($booking->movie_id == $selectedMovieId) ? 'accepted' : $booking->booking_status
//                 ]);
//         }
//     });

//     return response()->json(['message' => 'Booking confirmed successfully! Competing bookings have been canceled.'], 200);
// }

public function confirmBookings(Request $request)
{
    $placementId = $request->input('placement_id');
    $durationId = $request->input('duration_id');
    $selectedMovieId = $request->input('selected_movie');

    $competingBookings = DB::table('bookings')
        ->join('bookings_detail', function ($join) use ($placementId, $durationId) {
            $join->on('bookings.id', '=', 'bookings_detail.booking_id')
                ->where('bookings_detail.placement_id', $placementId)
                ->where('bookings_detail.duration_id', $durationId);
        })
        ->select('bookings.id', 'bookings.movie_id', 'bookings_detail.id as detail_id', 'bookings_detail.booking_status')
        ->get();

    if ($competingBookings->isEmpty()) {
        return response()->json(['error' => 'No bookings for this placement and duration'], 404);
    }

    DB::transaction(function () use ($competingBookings, $selectedMovieId) {
        foreach ($competingBookings as $booking) {
            DB::table('bookings_detail')
                ->where('id', $booking->detail_id)
                ->update([
                    'booking_status' => ($booking->movie_id == $selectedMovieId) ? 'accepted' : $booking->booking_status
                ]);
        }
    });

    return redirect()->back()->with([
        "success" => true,
        "message" => "Update Data Succesfully"
    ]);
}


    //Get Bookings Manager
    public function getBookingManagerPageload()
    {
        try{
            $bookings = DB::table('bookings_detail as bd')
            ->select(
                'bd.id as detail_id',
                'bookings.id as booking_id',
                'movies.movies_name',
                'cinemas.cinema_name',
                'cinema_placements.placement_name',
                'durations.start_date',
                'bd.booking_status'
            )
            ->join('bookings', 'bd.booking_id', '=', 'bookings.id')
            ->join('movies', 'bookings.movie_id', '=', 'movies.id')
            ->join('cinema_placements', 'bd.placement_id', '=', 'cinema_placements.id')
            ->join('cinemas', 'cinema_placements.cinema_id', '=', 'cinemas.id')
            ->join('durations', 'bd.duration_id', '=', 'durations.id')
            ->where('bookings.user_id', Auth::id())
            ->orderBy('bookings.created_at', 'desc')
            ->paginate(15); 
            return Inertia::render('DistributorDashboard/BookingsTable', ['bookings' => $bookings]);
        }catch(\Exception $e){
            return Inertia::render('DistributorDashboard/BookingsTable', ['error' => $e]);
        }
       
    }


    //Create Bookings from Booking Calendar
    // public function createBookings(Request $request)
    // {
    //     $validated = $request->validate([
    //         'movie_id' => 'required|exists:movies,id',
    //         'selected_bookings' => 'required|array',
    //         'selected_bookings.*.placementId' => 'exists:cinema_placements,id',
    //         'selected_bookings.*.durationId' => 'exists:durations,id'
    //     ]);
    //     // Create the booking
    //     $booking = Bookings::create([
    //         'movie_id' => $validated['movie_id'],
    //         'user_id' => Auth::id(),
    //         'status' => 'pending'
    //     ]);
    //     //start Create Bookings Details From Bookings Id.
    //     if (!empty($booking)) {
    //         foreach ($validated['selected_bookings'] as $selection) {
    //             $bookings = bookings_detail::create([
    //                 'booking_id' => $booking->id,
    //                 'placement_id' => $selection['placementId'],
    //                 'duration_id' => $selection['durationId']
    //             ]);
    //         }
    //     }
    //     if ($bookings) {
    //         return response()->json(['message' => 'Booking successfully created!'], 201);
    //     } else {
    //         return response()->json(['message' => 'Booking Creating failed'], 500);
    //     }
    // }

    public function createBookings(Request $request)
{
    $validated = $request->validate([
        'movie_id' => 'required|exists:movies,id',
        'selected_bookings' => 'required|array',
        'selected_bookings.*.placementId' => 'exists:cinema_placements,id',
        'selected_bookings.*.durationId' => 'exists:durations,id',
        'selected_bookings.*.Pending' => 'boolean' // Check if it's Pending (cancellation)
    ]);

    // Get the user ID
    $userId = Auth::id();

    // Create a booking record if there are new bookings (not cancellations)
    if (collect($validated['selected_bookings'])->contains('Pending', 0)) {
        $booking = Bookings::create([
            'movie_id' => $validated['movie_id'],
            'user_id' => $userId,
            'status' => 'pending'
        ]);
    } else {
        $booking = null; // No need to create a new booking if all are cancellations
    }

    // Process bookings (Create or Cancel)
    foreach ($validated['selected_bookings'] as $selection) {
        if ($selection['Pending'] === 0) {
            // ✅ Create new booking details (if it's NOT Pending)
            bookings_detail::create([
                'booking_id' => $booking->id,
                'placement_id' => $selection['placementId'],
                'duration_id' => $selection['durationId']
            ]);
        } else {
            // ❌ Cancel existing booking if it is Pending
            bookings_detail::where([
                'placement_id' => $selection['placementId'],
                'duration_id' => $selection['durationId']
            ])->delete();
        }
    }

    return response()->json([
        'message' => 'Booking processed successfully!'
    ], 201);
}


}
