<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DistributorController extends Controller
{
    public function getMoviePageLoad()
    {
        return Inertia::render('DistributorDashboard/addMovie');
    }

    public function getMovieTablePageLoad()
    {
        return Inertia::render('DistributorDashboard/MovieTable');
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
