<?php
namespace App\Http\Controllers;
use App\Models\cinema;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CinemaController extends Controller
{

    public function getCinemaPageload(){
        $dataList = cinema::all();
        return Inertia::render('Dashboard.CreateCompanyPage', ['dataList' => $dataList]);
    }
    
    private function getCinemasTable(){
        $dataList = cinema::all();
        return Inertia::render('Dashboard.CreateCompanyPage', ['dataList' => $dataList]);
        // Additional logic can be added here if needed
    }

    public function addCinemas(Request $request){
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'distributer' => 'required|string|max:255',
        ]);

        Cinema::create([
            'cinema_name' => $validatedData['name'],
            'cinema_address' => $validatedData['address'],
            'cinema_contact' => $validatedData['contact'],
            'cinema_ditributer' => $validatedData['distributer'],
        ]);

        return $this->getCinemasTable();
    }


}
