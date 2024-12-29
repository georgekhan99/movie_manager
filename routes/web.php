<?php

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Controller as RoutingController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Company;

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
    Route::get('dashboard/adduser', [App\Http\Controllers\CinemaController::class, 'getCinemaPageload'])->name('dashboard.adduser');
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
    Route::get('/dashboard/company/addusers',[App\Http\Controllers\CompanyController::class, 'getUsers'])->name('company.addusers');
    Route::post('/dashboard/add/attachusers',[App\Http\Controllers\CompanyController::class, 'AttacheUserToCompany'])->name('add.company_users');
    Route::get('/dashboard/get/company/{id}/users',[App\Http\Controllers\CompanyController::class, 'getCompanyUsersdata'] )->name('get.attached.users');
    Route::get('/dashboard/delete/company/{id}/users', [App\Http\Controllers\CompanyController::class, 'DeleteCompanyUsersdata'] )->name('delete.company_user');

    //Admin Cinema
    Route::get('/dashboard/cinema/create', [App\Http\Controllers\CinemaController::class, 'CreateCinemas'])->name('create.cinemas.noid');
    Route::get('/dashboard/cinema/create/{id}', [App\Http\Controllers\CinemaController::class, 'CreateCinemasWithId'])->name('adminpage.cinema.create.withid');
    //All Cinema
    Route::get('/dashboard/cinema/', [App\Http\Controllers\CinemaController::class, 'getCinemaPageLoad'])->name('adminpage.cinema.AllCinemas');
    Route::post('/dashboard/addcinema', [App\Http\Controllers\CinemaController::class, 'saveCinema'])->name('admin.save_cinema');

    //Placements
    Route::post('/dashboard/save/placement', [App\Http\Controllers\CinemaController::class, 'savePlacement'])->name('cinemas.save.placements');



    //Distributor Manager
    Route::get('/distributor/add/movie', [App\Http\Controllers\DistributorController::class, 'getMoviePageLoad'])->name('distributor.add.movie');

    
    
   
});




