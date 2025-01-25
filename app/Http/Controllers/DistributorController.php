<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\movies;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DistributorController extends Controller
{
    public function getMoviePageLoad()
    {
       $Company_list = Company::all(['id','company_legalname']);
        return Inertia::render('DistributorDashboard/addMovie', ['company_list' => $Company_list]);
    }

    public function addMovie(Request $request){
        try{
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

    public function getMovieTablePageLoad()
    {   
        $movie_list = movies::all();
        return Inertia::render('DistributorDashboard/MovieTable', ['movie_list' => $movie_list]);
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
