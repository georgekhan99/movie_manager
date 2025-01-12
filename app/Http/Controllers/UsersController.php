<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{

    public function getUserTable(Request $request)
    {
        // Get sorting parameters
        $sortColumn = $request->input('sortColumn', 'users.name'); // Default to sorting by name
        $sortDirection = $request->input('sortDirection', 'asc'); // Default to ascending order
        // Fetch and sort data
        $usersList = DB::table('user_roles')
            ->join('users', 'user_roles.id', '=', 'users.user_role_id')
            ->leftJoin('company', 'users.user_company_id', '=', 'company.id')
            ->select('users.id','users.name', 'users.surname', 'user_roles.name as role', 'company.company_legalname')
            ->orderBy($sortColumn, $sortDirection)
            ->get();
        return Inertia::render('AdminDashboard/UserTable', [
            'usersList' => $usersList,
            'filters' => [
                'sortColumn' => $sortColumn,
                'sortDirection' => $sortDirection,
            ],
        ]);
    }

    public function createUserPage()
    {
        $roleList = UserRole::all();
        $company_list = Company::all();
        return Inertia::render('AdminDashboard/CreateUserPage', ['users_role' => $roleList, 'company_list' => $company_list]);
    }

    public function createUser(Request $request)
    {
        $validate = $request->validate([
            'Name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'Surname' => ['required', 'string', 'max:255'],
            'Email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'Role' => ['required', 'integer', 'max:255'],
            'Password' => ['required', 'string', 'max:255'],
            'Retype' => ['required', 'string', 'max:255'],
        ]);

        if ($request->Password !== $request->Retype) {
            return redirect()->back()->withErrors(['Retype' => 'The password confirmation does not match.'])->withInput();
        }

        if ($validate) {
            User::create([
                'name' => $request->Name,
                'surname' => $request->Surname,
                'email' => $request->Email,
                'user_role_id' => $request->Role,
                'password' => bcrypt($request->Password),
                'user_company_id' => $request->Company,
            ]);
        }
    }

    public function editUserPage($id)
    {
        $user = User::findOrFail($id);
        $roleList = UserRole::all();
        $company_list = Company::all();

        return Inertia::render('AdminDashboard/EditUserPage', [
            'user' => $user,
            'roleList' => $roleList,
            'company_list' => $company_list
        ]);
    }

    public function updateUser(Request $request)
    {
        if ($request->Password && $request->Password !== $request->Retype) {
            return redirect()->back()->withErrors(['Retype' => 'The password confirmation does not match.'])->withInput();
        }
        $id = $request->id;
        $user = User::findOrFail($id);
        $user->name = $request->Name;
        $user->surname = $request->Surname;
        $user->email = $request->Email;
        $user->user_role_id = $request->Role;
        $user->user_company_id = $request->Company;
        if ($request->Password) {
            $user->password = bcrypt($request->Password);
        }
        $user->save();
        return redirect()->route('adminpage.userlist')->with('success', 'User updated successfully.');
    }


    public function deleteUser($id)
    {
        DB::table('company_users')->where('users_id', '=', $id)->delete();
        DB::table('users')->where('id', '=', $id)->delete();
        
        return redirect()->route('adminpage.userlist')->with('success', 'User deleted successfully.');

    }
}
