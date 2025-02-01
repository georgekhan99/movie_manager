<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Duration;

class BookingsController extends Controller
{
    public function getCalendarPageload(){
        
        return Inertia::render('AdminDashboard/BookingCalendar/CalendarPage');
    }

    public function showDuration(){
        //ประกาศวันที่เริ่มต้น;
        $duration = Duration::all();
        return Inertia::render('AdminDashboard/BookingCalendar/Duration',[
            'duration' => $duration 
        ]
    );
    }

    public function UpdateDeadline(Request $request, $id){
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
}
