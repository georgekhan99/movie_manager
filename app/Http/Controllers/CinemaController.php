<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Cinemas;
use App\Models\Company;
use App\Models\Placement;

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

    public function CreateCinemas()
    {
        $companyList = Company::all();

        return Inertia::render('AdminDashboard/CinemaManager/CreateCinema', [
            "companyList" => $companyList
        ]);
    }

    public function getCinemaListPageload($id){
        $cinema = DB::table('cinemas')
        ->join('cinema_placements', 'cinemas.id', '=', 'cinema_placements.cinema_id')
        ->where('cinemas.id', $id)
        ->select('cinemas.*', 
        'cinema_placements.placement_name as placement_name',
        'cinema_placements.placement_width as placement_width',
        'cinema_placements.placement_height as placement_height',
        'cinema_placements.placement_colors as placement_colors',
        'cinema_placements.placement_material as placement_material',
        )
        ->get();
        return Inertia::render('AdminDashboard/CinemaManager/Cinema.PlacementList', [
            "PageId" => $id,
            "CinemaData" => $cinema
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

    public function saveCinema(Request $request)
    {
        $cinema = array();
        $cinema['cinema_name'] = $request->cinemas[0]['name'];
        $cinema['address_1'] = $request->cinemas[0]['address_1'];
        $cinema['address_2'] = $request->cinemas[0]['address_2'];
        $cinema['zip'] = $request->cinemas[0]['zip'];
        $cinema['city'] = $request->cinemas[0]['city'];
        $cinema['state'] = $request->cinemas[0]['state'];
        $cinema['country'] = $request->cinemas[0]['country'];
        $cinema['company_id'] = $request->cinemas[0]['company_id'];
        // $validated = $request->validate([
        //     'cinemas.0.name' => 'required|string|max:255',
        //     'cinemas.0.address_1' => 'required|string|max:255',
        //     'cinemas.0.address_2' => 'nullable|string|max:255',
        //     'cinemas.0.zip' => 'required|string|max:10',
        //     'cinemas.0.city' => 'required|string|max:255',
        //     'cinemas.0.state' => 'required|string|max:255',
        //     'cinemas.0.country' => 'required|string|max:255',
        //     'cinemas.0.company' => 'string|max:255',
        //     ]);
        // Save the validated data to the database
        if (!empty($cinema)) {
            $insert = Cinemas::create($cinema);
            return redirect('/dashboard/cinema/create/' . $insert->id);
        }

        return response()->json('Nothing Data have yet');
    }

    public function savePlacement(Request $request)
    {
        if (!empty($request->placement[0]['cinema_id'])) {
            $placement = array();
            $placement['cinema_id']             = $request->placement[0]['cinema_id'];
            $placement['placement_name']        = $request->placement[0]['name'];
            $placement['placement_description'] = $request->placement[0]['description'];
            $placement['placement_width']       = $request->placement[0]['width'];
            $placement['placement_height']      = $request->placement[0]['height'];
            $placement['placement_colors']      = $request->placement[0]['colors'];
            $placement['placement_material']    = $request->placement[0]['material'];
            $placement['placement_price']       = $request->placement[0]['price'];
            Placement::create($placement);
        }
    }
}
