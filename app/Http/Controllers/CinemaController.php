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
    
        $request->validate([
            'cinema_name' => 'required|string',
            'address_1' => 'nullable|string',
            'address_2' => 'nullable|string',
            'zip' => 'nullable|string',
            'city' => 'required|string',
            'state' => 'nullable|string',
            'country' => 'required|string',
            'company_id' => 'nullable|integer',
            'image' => 'nullable|file|image|max:2048', // Validate image
        ]);

        $cinema = $request->only([
            'cinema_name', 'address_1', 'address_2', 'zip', 'city', 'state', 'country', 'company_id'
        ]);

        if($request->hasfile('image')) {
            $path = $request->file('image')->store('cinema_images', 'public');
            $cinema['image'] = $path;
        }
    
        $insert = Cinemas::create($cinema);

        if ($request->has('placements')) {
            $placements = json_decode($request->placements, true);
        
            foreach ($placements as $placementData) {
                $placement = new Placement();
                $placement->cinema_id = $insert->id; // Associate with the cinema
                $placement->placement_name = $placementData['name'];
                $placement->placement_description = $placementData['description'];
                $placement->placement_height = $placementData['height'];
                $placement->placement_width = $placementData['width'];
                $placement->placement_colors = $placementData['colors'];
                $placement->placement_material = $placementData['material'];
                $placement->placement_price = $placementData['price'];
        
                // Handle image if provided
                // if (isset($placementData['image'])) {
                //     $placement->image = $placementData['image'];
                // }
        
                $placement->save();
            }
        }
        return redirect('/dashboard/cinema/create/' . $insert->id)
        ->with('success', 'Cinema created successfully.');
    }

}
