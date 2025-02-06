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

    //Get Bookings Manager
    public function getBookingManagerPageload() {
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
    public function createBookings(Request $request) {
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
    
        // Store placements & durations
        foreach ($validated['selected_bookings'] as $selection) {
            Booking_Placement::create([
                'booking_id' => $booking->id,
                'placement_id' => $selection['placementId']
            ]);
            Booking_Duration::create([
                'booking_id' => $booking->id,
                'duration_id' => $selection['durationId']
            ]);
        }
    
        return response()->json(['message' => 'Booking successfully created!'], 201);
    }
}
