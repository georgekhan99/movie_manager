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

    public function UpdateDeadline(Request $request, $id)
    {
        $duration = Duration::find($id);
        if ($duration) {
            $Date = Carbon::create($request->new_date);
            $duration->update([
                'production_deadline' => $Date,
            ]);
            return redirect()->back()->with(['message' => 'Deadline updated successfully']);
        }
        return response()->json(['message' => 'Duration not found'], 404);
    }

    //Get placement each placement status and duration
    // public function getPlcementStatus($id, Request $request)
    // {
    //     $statusFilter = $request->query('status', 'all');
    //     // Get Placement Details
    //     $placement = DB::table('cinema_placements as cp')
    //         ->where('cp.id', $id)
    //         ->first(['cp.id as placement_id', 'cp.placement_name']);

    //     if (!$placement) {
    //         return redirect()->route('dashboard')->with('error', 'Placement not found');
    //     }

    //     // Fetch All Durations
    //     $durationsQuery = DB::table('durations as d')
    //     ->select(
    //         'd.id as duration_id',
    //         'd.start_date',
    //         'd.delivery_date',
    //         DB::raw("
    //             CASE 
    //                 WHEN EXISTS (
    //                     SELECT 1 
    //                     FROM bookings_detail bd
    //                     JOIN bookings b ON bd.booking_id = b.id
    //                     WHERE bd.placement_id = ? 
    //                     AND bd.duration_id = d.id
    //                     AND b.status = 'accepted'
    //                 ) THEN 'Accepted'

    //                 WHEN EXISTS (
    //                     SELECT 1 
    //                     FROM bookings_detail bd
    //                     JOIN bookings b ON bd.booking_id = b.id
    //                     WHERE bd.placement_id = ? 
    //                     AND bd.duration_id = d.id
    //                     AND b.status = 'pending'
    //                 ) THEN 'Pending'

    //                 ELSE 'Available'
    //             END AS status"
    //         ),
    //         DB::raw("(SELECT movies.movies_name 
    //                   FROM movies 
    //                   JOIN bookings b ON movies.id = b.movie_id 
    //                   JOIN bookings_detail bd ON b.id = bd.booking_id 
    //                   WHERE bd.duration_id = d.id 
    //                   LIMIT 1) AS movie_name")
    //     )
    //     ->setBindings([$id, $id]);
    //     if ($statusFilter !== 'all') {
    //         $durationsQuery->having('status', '=', ucfirst($statusFilter));
    //     }
    //     $durations = $durationsQuery->get();
    //     return Inertia::render(
    //         'MoviesManager/MovieStatusDetail',
    //         [
    //             'placement' => $placement,
    //             'durations' => $durations,
    //             'statusFilter' => $statusFilter
    //         ]
    //     );
    // }
    public function getPlcementStatus($id, Request $request)
    {
        $statusFilter = $request->query('status', 'all');

        // ✅ Get Placement Details
        $placement = DB::table('cinema_placements as cp')
            ->where('cp.id', $id)
            ->first(['cp.id as placement_id', 'cp.placement_name']);

        if (!$placement) {
            return redirect()->route('dashboard')->with('error', 'Placement not found');
        }

        // ✅ Fetch All Durations with **Multiple** Booking Status
        $durationsQuery = DB::table('durations as d')
            ->select(
                'd.id as duration_id',
                'd.start_date',
                'd.delivery_date',

                // ✅ Show all movies competing for this placement & duration
                DB::raw("(SELECT GROUP_CONCAT(m.movies_name ORDER BY b.status DESC SEPARATOR ', ') 
                          FROM movies m
                          JOIN bookings b ON m.id = b.movie_id 
                          JOIN bookings_detail bd ON b.id = bd.booking_id 
                          WHERE bd.placement_id = ? 
                          AND bd.duration_id = d.id) AS movies_competing"),

                // ✅ Correct booking status, showing if **any movie is booked**
                DB::raw(
                    "
                    CASE 
                        WHEN EXISTS (
                            SELECT 1 
                            FROM bookings_detail bd
                            JOIN bookings b ON bd.booking_id = b.id
                            WHERE bd.placement_id = ? 
                            AND bd.duration_id = d.id
                            AND b.status = 'accepted'
                        ) THEN 'Accepted'
    
                        WHEN EXISTS (
                            SELECT 1 
                            FROM bookings_detail bd
                            JOIN bookings b ON bd.booking_id = b.id
                            WHERE bd.placement_id = ? 
                            AND bd.duration_id = d.id
                            AND b.status = 'pending'
                        ) THEN 'Pending'
    
                        ELSE 'Available'
                    END AS status"
                )
            )
            ->setBindings([$id, $id, $id]);

        if ($statusFilter !== 'all') {
            $durationsQuery->having('status', '=', ucfirst($statusFilter));
        }

        $durations = $durationsQuery->get();

        return Inertia::render(
            'MoviesManager/MovieStatusDetail',
            [
                'placement' => $placement,
                'durations' => $durations,
                'statusFilter' => $statusFilter
            ]
        );
    }



    //Get Bookings Manager
    public function getBookingManagerPageload()
    {
        $bookings = DB::table('bookings')
            ->select(
                'bookings.id',
                'bookings.status',
                'movies.movies_name',
                'cinemas.cinema_name',
                'cinema_placements.placement_name',
                'durations.start_date'
            )
            ->join('movies', 'bookings.movie_id', '=', 'movies.id')
            ->join('booking_placements', 'bookings.id', '=', 'booking_placements.booking_id')
            ->join('cinema_placements', 'booking_placements.placement_id', '=', 'cinema_placements.id')
            ->join('cinemas', 'cinema_placements.cinema_id', '=', 'cinemas.id')
            ->join('booking_durations', 'bookings.id', '=', 'booking_durations.booking_id')
            ->join('durations', 'booking_durations.duration_id', '=', 'durations.id')
            ->where('bookings.user_id', Auth::id()) // Fetch only user's bookings
            ->orderBy('bookings.created_at', 'desc')
            ->get();

        return Inertia::render('DistributorDashboard/BookingsTable', ['bookings' => $bookings]);
    }


    //Create Bookings from Booking Calendar
    public function createBookings(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'selected_bookings' => 'required|array',
            'selected_bookings.*.placementId' => 'exists:cinema_placements,id',
            'selected_bookings.*.durationId' => 'exists:durations,id'
        ]);
        // Create the booking
        $booking = Bookings::create([
            'movie_id' => $validated['movie_id'],
            'user_id' => Auth::id(),
            'status' => 'pending'
        ]);
        //start Create Bookings Details From Bookings Id.
        if (!empty($booking)) {
            foreach ($validated['selected_bookings'] as $selection) {
                $bookings = bookings_detail::create([
                    'booking_id' => $booking->id,
                    'placement_id' => $selection['placementId'],
                    'duration_id' => $selection['durationId']
                ]);
            }
        }
        if ($bookings) {
            return response()->json(['message' => 'Booking successfully created!'], 201);
        } else {
            return response()->json(['message' => 'Booking Creating failed'], 500);
        }
    }
}
