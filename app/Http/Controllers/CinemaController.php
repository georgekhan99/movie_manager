<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Cinemas;
use App\Models\Company;
use App\Models\Placement;
use App\Models\bookings_detail;
use App\Models\Duration;
use App\Models\movies;
use Carbon\Carbon;

class CinemaController extends Controller
{
    public function getCinemaPageLoad()
    {
        $cinemaList = DB::table('cinemas')
            ->get();
        return Inertia::render('AdminDashboard/CinemaManager/AllCinemas', [
            "cinemaList" => $cinemaList,
        ]);
    }

    //Get Mycinema Page
    public function getMycinemaPageload()
    {
        //Fetch Only you Owner Cinemas
        $cinemaList = DB::table('cinemas')->limit(3)->get();


        return Inertia::render('MoviesManager/MyCinemaPage', [
            'cinemaList' => $cinemaList
        ]);
    }


    public function getCinemaBookingDetailPageload($cinemaId)
    {
        $cinema = DB::table('cinemas')
            ->where('id', $cinemaId)
            ->first(['id', 'cinema_name']);

        if (!$cinema) {
            return redirect()->route('dashboard')->with('error', 'Cinema not found');
        }

        $placements = DB::table('cinema_placements as cp')
            ->select(
                'cp.id as placement_id',
                'cp.placement_name',

                DB::raw("(SELECT COUNT(*) FROM durations) AS total_durations"),

                DB::raw("(SELECT COUNT(DISTINCT bd.duration_id) 
                          FROM bookings_detail bd
                          WHERE bd.placement_id = cp.id 
                          AND bd.booking_status = 'accepted') AS booked_count"),

                DB::raw("(SELECT COUNT(DISTINCT bd.duration_id) 
                          FROM bookings_detail bd
                          WHERE bd.placement_id = cp.id 
                          AND bd.booking_status = 'pending') AS pending_count"),

                DB::raw("(SELECT COUNT(*) FROM durations d
                          WHERE d.id NOT IN (
                              SELECT DISTINCT bd.duration_id
                              FROM bookings_detail bd
                              WHERE bd.placement_id = cp.id 
                              AND bd.booking_status IN ('pending', 'accepted')
                          )
                        ) AS available_count"),

                // Include booking_id and accepted movie
                DB::raw("(SELECT GROUP_CONCAT(DISTINCT b.id) 
                          FROM bookings b
                          JOIN bookings_detail bd ON b.id = bd.booking_id
                          WHERE bd.placement_id = cp.id
                          AND bd.booking_status = 'accepted') AS booking_id"),

                DB::raw("(SELECT m.movies_name 
                          FROM movies m
                          JOIN bookings_detail bd ON m.id = bd.movie_id
                        WHERE bd.placement_id = cp.id
                      AND bd.booking_status = 'accepted'
                      LIMIT 1) AS accepted_movie")
            )
            ->where('cp.cinema_id', $cinemaId)
            ->groupBy('cp.id')
            ->get()
            ->map(function ($item) {
                if ($item->booked_count > 0) {
                    unset($item->movies_competing);
                }
                return $item;
            });

        return Inertia::render('MoviesManager/CinemaBookingDetail', [
            'cinema' => $cinema,
            'placements' => $placements,
        ]);
    }



    function CreateCinemasWithId($id)
    {
        $companyList = Company::all();
        $CinemasList = Cinemas::where('id', $id)
            ->first();
        return Inertia::render('AdminDashboard/CinemaManager/CreateCinema', [
            "CinemasList" => $CinemasList,
            "companyList" => $companyList,
            "Cinemas_id" => $id
        ]);
    }

    public function CreateCinemas()
    {
        $companyList = Company::all();
        return Inertia::render('AdminDashboard/CinemaManager/CreateCinema', [
            "companyList" => $companyList
        ]);
    }

    public function getPlacementByID($id)
    {
        $placements = Placement::find($id);
        return response()->json($placements);
    }

    public function getCinemaListPageload($id)
    {
        $cinema = DB::table('cinemas')
            ->join('cinema_placements', 'cinemas.id', '=', 'cinema_placements.cinema_id')
            ->where('cinemas.id', $id)
            ->select(
                'cinema_placements.id as placement_ID',
                'cinema_placements.placement_name as placement_name',
                'cinema_placements.placement_width as placement_width',
                'cinema_placements.placement_height as placement_height',
                'cinema_placements.placement_colors as placement_colors',
                'cinema_placements.placement_material as placement_material',
            )
            ->get();
        $cinema_data = DB::table('cinemas')->where('id', $id)->first();
        if (isset($cinema_data->Difference_Address)) {
            $cinema_data->Difference_Address = json_decode($cinema_data->Difference_Address, true);
        } else {
            $cinema_data->Difference_Address = []; // Default to an empty array
        }
        return Inertia::render('AdminDashboard/CinemaManager/Cinema.PlacementList', [
            "PageId" => $id,
            "CinemaData" => $cinema,
            "cinema_data" => $cinema_data,
        ]);
    }

    public function saveCinema(Request $request)
    {
        $request->validate([
            'cinema_name' => 'required|string',
            'address_1' => 'nullable|string',
            'address_2' => 'nullable|string',
            'zip' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'nullable|string',
            'country' => 'required|string',
            'company_id' => 'nullable|integer',
            'image' => 'nullable|file|image|max:2048', // Validate image.
            'difference_addresses' => 'nullable|json', // Ensure valid JSON
        ]);
        $cinema = $request->only([
            'cinema_name',
            'address_1',
            'address_2',
            'zip',
            'city',
            'state',
            'country',
            'company_id',
            'Difference_Address'
        ]);
        if ($request->hasfile('image')) {
            $path = $request->file('image')->store('cinema_images', 'public');
            $cinema['image'] = $path;
        }
        if ($request->has('difference_addresses')) {
            $cinema['Difference_Address'] = json_encode($request->difference_addresses);
        }
        $insert = Cinemas::create($cinema);
        if ($request->has('placements')) {
            $placements = json_decode($request->placements, true);
            foreach ($placements as $index => $placementData) {
                $placement = new Placement();
                $placement->cinema_id = $insert->id; // Associate with the cinema
                $placement->placement_name = $placementData['name'];
                $placement->placement_description = $placementData['description'];
                $placement->placement_height = $placementData['height'];
                $placement->placement_width = $placementData['width'];
                $placement->placement_colors = $placementData['colors'];
                $placement->placement_material = $placementData['material'];
                $placement->placement_price = $placementData['price'];
                $fileKey = "placement_image_{$index}";
                if ($request->hasFile($fileKey)) {
                    $path = $request->file($fileKey)->store('placements_images', 'public');
                    $placement->placement_image = $path;
                }
                $placement->save();
            }
        }
        return redirect('/dashboard/cinema/create/' . $insert->id)
            ->with('success', 'Cinema created successfully.');
    }

    public function saveMorePlacement(Request $request)
    {
        $placements = $request->input('placements');
        $savedPlacements = [];
        try {
            foreach ($placements as $index => $placementData) {
                $placement = new Placement();
                $placement->cinema_id = $request->Cinema_id;
                $placement->placement_name = $placementData['name'] ?? null;
                $placement->placement_description = $placementData['description'] ?? null;
                $placement->placement_height = $placementData['height'] ?? null;
                $placement->placement_width = $placementData['width'] ?? null;
                $placement->placement_colors = $placementData['colors'] ?? null;
                $placement->placement_material = $placementData['material'] ?? null;
                $placement->placement_price = $placementData['price'] ?? null;
                $placement->placement_type = $placementData['type'] ?? null;
                $placement->placement_finishing = $placementData['finishing'] ?? null;
                $placement->placement_shipping_price = $placementData['shipping_price'] ?? null;
                // Handle image file upload
                $fileKey = "placements.$index.image";
                if ($request->hasFile($fileKey)) {
                    $path = $request->file($fileKey)->store('placements_images', 'public');
                    $placement->placement_image = $path;
                }
                // Save placement
                $placement->save();
                $savedPlacements[] = $placement;
            }
            return redirect('/dashboard/cinema/' . $request->Cinema_id . '/view')->with(['success' => 'Data has been saved']);
        } catch (\Exception $e) {
            return redirect()->back()->with(["fail" => $e]);
        }
    }

    public function updateCinema(Request $request)
    {
        try {
            $data = array();
            $data['cinema_name'] = $request->cinema_name;
            $data['address_1'] = $request->address_1;
            $data['address_2'] = $request->address_2;
            $data['zip'] = $request->zip;
            $data['city'] = $request->city;
            $data['state'] = $request->state;
            $data['country'] = $request->country;
            $data['company_id'] = $request->company_id;
            if ($request->hasfile('image')) {
                $cinema_old_img = Cinemas::find($request->id);
                if ($cinema_old_img && $cinema_old_img->cinema_images) {
                    Storage::disk('public')->delete($cinema_old_img->cinema_images);
                }
                $path = $request->file('image')->store('cinema_images', 'public');
                $data['image'] = $path;
            }
            Cinemas::where('id', $request->id)->update($data);
            return redirect('/dashboard/cinema/' . $request->id . '/view')
                ->with('success', 'Cinema created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update placement: ' . $e->getMessage());
        }
    }

    //Update Placement Details
    public function updateplacement(Request $request)
    {
        try {
            $data = array();
            $data['placement_name'] = $request->placement_name;
            $data['placement_width'] = $request->placement_width;
            $data['placement_height'] = $request->placement_height;
            $data['placement_material'] = $request->placement_material;
            $data['placement_price'] = $request->placement_price;
            $data['placement_colors'] = $request->placement_colors;
            $data['placement_description'] = $request->placement_description;
            if ($request->hasFile('placement_image')) {
                //หา Path รูป
                $placement = Placement::find($request->placement_id);
                //ลบรูปเก่า
                if ($placement && $placement->placement_image) {
                    Storage::disk('public')->delete($placement->placement_image);
                }
                $path = $request->file('placement_image')->store('placements_images', 'public');
                $data['placement_image'] = $path;
            }
            Placement::where('id', $request->placement_id)->update($data);
            return redirect()->back()->with('success', 'Placement updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update placement: ' . $e->getMessage());
        }
    }

    public function getPageAddMorePlacement($id)
    {
        $cinema = Cinemas::where('id', $id)->first();
        if ($cinema) {
            $isCenemas  = $cinema;
        } else {
            $isCenemas = false;
        }
        return Inertia::render('AdminDashboard/CinemaManager/AddMorePlacement', ['placement_id' => $id, 'cinemas_data' => $isCenemas]);
    }

    public function deletePlacement(Request $request)
    {
        $data = Placement::where('id', $request->id);
        $data->delete();
        return redirect()->back()->with(['message' => 'Deleted Placement Successfulluy']);
    }







    // public function GetMovieMangagerCalendar(Request $request)
    // {
    //     $offset = $request->input('offset', 0);
    //     $limit = 5;

    //     $cinemas = DB::table('cinemas')
    //         ->join('cinema_placements', 'cinemas.id', '=', 'cinema_placements.cinema_id')
    //         ->select('cinemas.id', 'cinemas.cinema_name')
    //         ->distinct()
    //         ->offset($offset * $limit)
    //         ->limit($limit)
    //         ->get();

    //     $calendarData = [];

    //     foreach ($cinemas as $cinema) {
    //         $placements = DB::table('cinema_placements as cp')
    //             ->where('cp.cinema_id', $cinema->id)
    //             ->select('cp.id', 'cp.placement_name', 'cp.placement_width', 'cp.placement_height', 'cp.placement_price')
    //             ->get();

    //         $formattedPlacements = [];

    //         foreach ($placements as $placement) {
    //             $bookings = DB::table('bookings_detail as bd')
    //                 ->where('bd.placement_id', $placement->id)
    //                 ->leftJoin('movies as m', 'bd.movie_id', '=', 'm.id')
    //                 ->select(
    //                     'bd.duration_id',
    //                     'bd.booking_status as status',
    //                     'm.movies_name as movie_name',
    //                     'm.id as movie_id',
    //                     'm.movies_release_date'
    //                 )
    //                 ->get();

    //             $formattedPlacements[] = [
    //                 'placement_id' => $placement->id,
    //                 'placement_name' => $placement->placement_name,
    //                 'width' => $placement->placement_width,
    //                 'height' => $placement->placement_height,
    //                 'price' => $placement->placement_price,
    //                 'bookings' => $bookings
    //             ];
    //         }

    //         $calendarData[] = [
    //             'group_name' => $cinema->cinema_name,
    //             'placements' => $formattedPlacements
    //         ];
    //     }

    //     return Inertia::render('MoviesManager/MovieManagerCalendar', [
    //         'calendarData' => $calendarData,
    //         'currentOffset' => $offset,
    //     ]);
    // }

    public function GetMovieMangagerCalendar(Request $request)
    {
        $page = (int) $request->input('offset', 0);
        $limit = 10;
        $offset = $page * $limit;

        //Get All movies
        $movies = DB::table('movies')->select('id', 'movies_name')->get();
        $selectedMovieId = $request->input('movie_id');



        $cinemas = DB::table('cinemas')
            ->join('cinema_placements', 'cinemas.id', '=', 'cinema_placements.cinema_id')
            ->select('cinemas.id', 'cinemas.cinema_name')
            ->distinct()
            ->offset($offset)
            ->limit($limit)
            ->get();

        $calendarData = [];

        foreach ($cinemas as $cinema) {
            $placements = DB::table('cinema_placements as cp')
                ->where('cp.cinema_id', $cinema->id)
                ->select(
                    'cp.id',
                    'cp.placement_name',
                    'cp.placement_width',
                    'cp.placement_height',
                    'cp.placement_price'
                )
                ->get();

            $formattedPlacements = [];

            foreach ($placements as $placement) {
                $bookingsQuery  = DB::table('bookings_detail as bd')
                    ->where('bd.placement_id', $placement->id)
                    ->leftJoin('movies as m', 'bd.movie_id', '=', 'm.id')
                    ->leftJoin('durations as d', 'bd.duration_id', '=', 'd.id')
                    ->select(
                        'bd.id as booking_detail_id',
                        'bd.duration_id',
                        'bd.booking_status as status',
                        'm.movies_name as movie_name',
                        'm.id as movie_id',
                        'd.start_date as duration_start_date'
                    );

                if ($selectedMovieId) {
                    $bookingsQuery->where('m.id', $selectedMovieId);
                }

                $bookings = $bookingsQuery->get();

                $formattedPlacements[] = [
                    'placement_id' => $placement->id,
                    'placement_name' => $placement->placement_name,
                    'width' => $placement->placement_width,
                    'height' => $placement->placement_height,
                    'price' => $placement->placement_price,
                    'bookings' => $bookings,
                ];
            }

            $calendarData[] = [
                'group_name' => $cinema->cinema_name,
                'placements' => $formattedPlacements
            ];
        }

        return Inertia::render('MoviesManager/MovieManagerCalendar', [
            'calendarData' => $calendarData,
            'currentOffset' => $page,
            'selectedMovieId' => $selectedMovieId,
            'movies' => $movies,
        ]);
    }

    public function ConfirmBookingCinemaCalendar(Request $request){
        

    }

}
