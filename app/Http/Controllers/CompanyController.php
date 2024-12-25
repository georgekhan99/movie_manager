<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\label;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public function getLabel()
    {
        $labels = label::all();

        return response()->json($labels);
    }

    public function SavecompanyData(Request $request)
    {
        $validated = $request->validate([
            'label_name' =>  'required|integer',
            'Legalname'  => 'required|string',
            'Brandname' =>  'required|string',
            'Organization' =>  'required|string',
            'ParentCompany' =>  'required|string',
            'CVR' => 'required|string',
            'Address_1' => 'required|string',
            'Address_2' => 'required|string',
            'Zip_Code' =>  'required|integer',
            'City' => 'required|string',
            'Region' => 'required|string',
            'Country' => 'required|string'
        ]);
        if ($validated) {
            Company::create([
                'label_id' => $validated['label_name'],
                'company_legalname' => $validated['Legalname'],
                'company_brand_name' => $validated['Brandname'],
                'company_organization' => $validated['Organization'],
                'company_parent_company' => $validated['ParentCompany'],
                'company_CVR' => $validated['CVR'],
                'company_address_1' => $validated['Address_1'],
                'company_address_2' => $validated['Address_2'],
                'company_zip_code' => $validated['Zip_Code'],
                'company_city' => $validated['City'],
                'company_state' => $validated['Region'],
                'company_Country' => $validated['Country'],
            ]);
        }
    }

    public function getCompanyTableData(){
        $companyList = DB::table('label')
        ->join('company', 'label.id', '=', 'company.label_id')
        ->select('company.id','company.company_legalname as company_name', 'label.label_name as label')
        ->get();
        return response()->json($companyList);
    }

    public function EditCompanyData($id){
        // $CompanyData = Company::firstOrFail($id);
        $companyData = Company::find($id);
        return Inertia::render('AdminDashboard/Company/EditCompany',[
            'CompanyData' => $companyData
        ]);




    }

    
 
}
