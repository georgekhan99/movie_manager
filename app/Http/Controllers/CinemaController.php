<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use App\Models\Cinemas;
use App\Models\Company;

class CinemaController extends Controller
{
    public function getCinemaPageLoad(){
        $cinemaList = DB::table('cinemas')
        ->get();
        return Inertia::render('AdminDashboard/CinemaManager/AllCinemas', [
            "cinemaList" => $cinemaList,
        ]);
    }

    public function CreateCinemas() {
        $companyList = Company::all();

        return Inertia::render('AdminDashboard/CinemaManager/CreateCinema', [
            "companyList" => $companyList
        ]);
    }
    
    public function saveCinema(Request $request){
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
        $insert = Cinemas::create($cinema);
        return redirect('/dashboard/cinema/create/' . $insert->id);
        }
    
    public function savePlacement(Request $request){
        $placements = array();
        $placements['cinema_id']  = $request->cinema_id[0]['cinema_id'];
        $placements['placement_name'] = $request->cinema_id[0]['placement_name'];
        $placements['placement_description'] = $request->cinema_id[0]['placement_description'];
        $placements['placement_width'] = $request->cinema_id[0]['placement_width'];
        $placements['placement_height'] = $request->cinema_id[0]['placement_height'];
        $placements['placement_colors'] = $request->cinema_id[0]['placement_colors'];
        $placements['placement_material'] = $request->cinema_id[0]['placement_material'];
        $placements['placement_price'] = $request->cinema_id[0]['placement_price'];


        dd($placements);
        

        
    }
    

    }


