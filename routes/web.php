<?php

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller as RoutingController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
//Admin
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('AdminDashboard/Dashboard');
    })->name('adminpage.dashboard');
    //User Manager
    Route::get('/dashboard/createuser', [App\Http\Controllers\UsersController::class, 'createUserPage'])->name('adminpage.CreateUserPage');
    Route::get('/dashboard/user/list',[App\Http\Controllers\UsersController::class, 'getUserTable'])->name('adminpage.userlist');
    Route::get('/dashboard/user/{id}/edit',[App\Http\Controllers\UsersController::class, 'editUserPage'])->name('admin.edit.user');
    Route::post('/dashboard/create/user',[App\Http\Controllers\UsersController::class, 'createUser'])->name('create.user');
    Route::get('/dashboard/user/delete/{id}', [App\Http\Controllers\UsersController::class, 'deleteUser'])->name('delete.user');
    Route::post('/dashboard/user/update', [App\Http\Controllers\UsersController::class, 'updateUser'])->name('update.user');

    //Company 
    Route::get('/dashboard/company/list', function (){
        return Inertia::render('AdminDashboard/Company/CompanyManager');
    })->name('company.companyList');
    Route::get('/dashboard/getlabel', [App\Http\Controllers\CompanyController::class, 'getLabel'])->name('company.getlabel');
    Route::post('/dashboard/company/add', [App\Http\Controllers\CompanyController::class, 'SavecompanyData'])->name('company.save_company');
    Route::get('/dashboard/get/company/all',[App\Http\Controllers\CompanyController::class, 'getCompanyTableData'])->name('get.company.all');
    Route::get('/dashboard/company/{id}/edit', [App\Http\Controllers\CompanyController::class, 'EditCompanyData'])->name('company.edit');


    //Admin Cinema
    Route::get('/dashboard/cinema/create', function () {
        return Inertia::render('AdminDashboard/CinemaManager/CreateCinema');
    })->name('adminpage.cinema.create');
    //All Cinema
    Route::get('/dashboard/cinema', function () {
        return Inertia::render('AdminDashboard/CinemaManager/AllCinemas');
    })->name('adminpage.cinema.AllCinemas');

    Route::get('dashboard/adduser', [App\Http\Controllers\CinemaController::class, 'getCinemaPageload'])->name('dashboard.adduser');
});




