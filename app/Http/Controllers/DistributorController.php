<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DistributorController extends Controller
{
    public function getMoviePageLoad(){
        
        return Inertia::render('DistributorDashboard/addMovie');
    }
}
