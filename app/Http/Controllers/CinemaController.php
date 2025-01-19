<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        // dd($cinema);
        return Inertia::render('AdminDashboard/CinemaManager/Cinema.PlacementList', [
            "PageId" => $id,
            "CinemaData" => $cinema,
            "cinema_data" => $cinema_data
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
            'cinema_name',
            'address_1',
            'address_2',
            'zip',
            'city',
            'state',
            'country',
            'company_id'
        ]);
        if ($request->hasfile('image')) {
            $path = $request->file('image')->store('cinema_images', 'public');
            $cinema['image'] = $path;
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

    public function deletePlacement(Request $request)
    {
        $data = Placement::where('id', $request->id);
        $data->delete();
        return redirect()->back()->with(['message' => 'Deleted Placement Successfulluy']);
    }
}
