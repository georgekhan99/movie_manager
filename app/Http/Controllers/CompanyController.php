<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\label;
use App\Models\Company;
use App\Models\Company_users;
use App\Models\Cinemas;
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
            'Initials' =>  'required|string',
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
                'company_initials' => $validated['Initials'],
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

    public function getCompanyTableData()
    {
        $companyList = DB::table('label')
            ->join('company', 'label.id', '=', 'company.label_id')
            ->select('company.id', 'company.company_legalname as company_name', 'label.label_name as label')
            ->get();
        return response()->json($companyList);
    }

    public function EditCompanyData($id)
    {
        $companyData = Company::where('company.id', $id)
            ->leftJoin('label', 'company.label_id', '=', 'label.id')
            ->select(
                'company.id as id',
                'company.company_legalname as legalName',
                'company.company_brand_name as BrandName',
                'company.company_initials as Initials',
                'company.company_parent_company as Parent_Company',
                'company.company_CVR as CVR',
                'company.company_address_1 as address_1',
                'company.company_address_2 as address_2',
                'company.company_zip_code as zip_code',
                'company.company_city as City',
                'company.company_state as State',
                'company.company_Country as Country',
                'label.label_name',
                'label.id as label.id'
            )
            ->first();
        $labels = label::all();

        $CinemasData = DB::table('cinemas')
            ->where('cinemas.company_id', $id)
            ->select('id', 'cinema_name', 'company_id')
            ->get();

        return Inertia::render('AdminDashboard/Company/EditCompany', [
            'CompanyData' => $companyData,
            'labels' => $labels,
            'cinemasData' => $CinemasData
        ]);
    }

    public function updateCompany(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:company,id',
            'legalName' => 'required|string|max:255',
            'BrandName' => 'required|string|max:255',
            'Initials' => 'nullable|string|max:255',
            'Parent_Company' => 'nullable|string|max:255',
            'CVR' => 'nullable|string|max:100',
            'address_1' => 'nullable|string|max:255',
            'address_2' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'City' => 'nullable|string|max:100',
            'State' => 'nullable|string|max:100',
            'Country' => 'nullable|string|max:100',
            'lebel_id' => 'required|exists:label,id',
        ]);

        $company = Company::findOrFail($request->id);

        $company->company_legalname = $request->legalName;
        $company->company_brand_name = $request->BrandName;
        $company->company_initials = $request->Initials;
        $company->company_parent_company = $request->Parent_Company;
        $company->company_CVR = $request->CVR;
        $company->company_address_1 = $request->address_1;
        $company->company_address_2 = $request->address_2;
        $company->company_zip_code = $request->zip_code;
        $company->company_city = $request->City;
        $company->company_state = $request->State;
        $company->company_Country = $request->Country;
        $company->label_id = $request->lebel_id;

        $company->save();

        return redirect()->back()->with('success', 'Update Success Fully.');
    }

    public function deleteCompany(Request $request)
    {
        try {
            $companyId = $request->input('company_id');

            // ลบความสัมพันธ์จาก company_users
            DB::table('company_users')->where('company_id', $companyId)->delete();

            // ลบ movies ที่เกี่ยวข้อง
            DB::table('movies')->where('company_id', $companyId)->delete();

            // อัปเดต users ที่มี user_company_id ให้เป็น null
            DB::table('users')->where('user_company_id', $companyId)->update([
                'user_company_id' => null,
            ]);

            // อัปเดต cinemas ให้ company_id เป็น null
            DB::table('cinemas')->where('company_id', $companyId)->update([
                'company_id' => null,
            ]);

            // ลบบริษัท
            DB::table('company')->where('id', $companyId)->delete();

            return redirect()->back()->with('success', 'ลบบริษัทเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'เกิดข้อผิดพลาด: ' . $e->getMessage());
        }
    }


    // public function getUsers(){
    //     $users = DB::table('users')
    //     ->join('user_roles', 'user_roles.id', '=', 'users.user_role_id')
    //     ->select(['users.id', 'users.name','users.surname', 'user_roles.name as role_name', 'users.user_company_id'])
    //     ->get();
    //     return response()->json($users);

    // return response()->json($users);

    // }

    public function getUsers(Request $request)
    {
        $query = DB::table('users')
            ->join('user_roles', 'user_roles.id', '=', 'users.user_role_id')
            ->select([
                'users.id',
                'users.name',
                'users.surname',
                'user_roles.name as role_name',
                'users.user_company_id',
            ]);

        if ($request->has('role')) {
            $query->where('user_roles.name', $request->get('role'));
        }

        $users = $query->get();

        return response()->json($users);
    }

    public function AttacheUserToCompany(Request $request)
    {

        $validated = $request->validate([
            'users_id' => 'required|unique:company_users,company_id',
        ]);

        $data = Company_users::create([
            'users_id' => $validated['users_id'],
            'company_id' => $request->company_id
        ]);

        if ($data) {
            return redirect()->back()->with('success', 'User attached to company successfully.');
        } else {
            return redirect()->back()->withErrors(['error' => 'Failed to attach user to company.']);
        }
    }

    public function getCompanyUsersdata($companyId)
    {
        $users = Company_users::with(['user', 'company'])
            ->join('users', 'company_users.users_id', '=', 'users.id')
            ->join('user_roles', 'users.user_role_id', '=', 'user_roles.id')
            ->where('company_users.company_id', $companyId)
            ->select(
                'users.id',
                'users.name',
                'users.surname',
                'users.email',
                'user_roles.name as role_name',
                'company_users.created_at',
                'company_users.id as c_id'
            )
            ->orderBy('users.name')
            ->get();
        return response()->json($users);
    }

    public function DeleteCompanyUsersdata($id)
    {
        $data = Company_users::where('id', $id)->delete();

        if ($data) {
            return redirect()->back()->withInput()->with('success', 'User Deleted Successfully');
        } else {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to Delete User']);
        }
    }
}
