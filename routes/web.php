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
    return redirect('/dashboard');
});
//Admin
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    Route::get('dashboard/adduser', [App\Http\Controllers\CinemaController::class, 'getCinemaPageload'])->name('dashboard.adduser');
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard.index');
    })->name('adminpage.dashboard');
    Route::get('/dashboard/addcomapny', function () {
        return Inertia::render('AdminDashboard/Dashboard');
    })->name('adminpage.dashboard.add.company');
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
    Route::get('/dashboard/get/company/all',[App\Http\Controllers\CompanyController::class, 'getCompanyTableData'])->name('get.company.all');
    Route::get('/dashboard/company/{id}/edit', [App\Http\Controllers\CompanyController::class, 'EditCompanyData'])->name('company.edit');
    Route::get('/dashboard/company/addusers',[App\Http\Controllers\CompanyController::class, 'getUsers'])->name('company.addusers');
    Route::get('/dashboard/get/company/{id}/users',[App\Http\Controllers\CompanyController::class, 'getCompanyUsersdata'] )->name('get.attached.users');
    Route::get('/dashboard/delete/company/{id}/users', [App\Http\Controllers\CompanyController::class, 'DeleteCompanyUsersdata'] )->name('delete.company_user');
    Route::post('/dashboard/add/attachusers',[App\Http\Controllers\CompanyController::class, 'AttacheUserToCompany'])->name('add.company_users');
    Route::post('/dashboard/company/add', [App\Http\Controllers\CompanyController::class, 'SavecompanyData'])->name('company.save_company');

    //Admin Cinema
    Route::get('/dashboard/cinema/create', [App\Http\Controllers\CinemaController::class, 'CreateCinemas'])->name('create.cinemas.noid');
    Route::get('/dashboard/cinema/create/{id}', [App\Http\Controllers\CinemaController::class, 'CreateCinemasWithId'])->name('adminpage.cinema.create.withid');
    Route::get('/dashboard/cinema/{id}/view', [App\Http\Controllers\CinemaController::class, 'getCinemaListPageload'])->name('adminpage.cinema.placements.list');
    Route::get('/dashboard/placement/{id}', [App\Http\Controllers\CinemaController::class, 'getPlacementByID'])->name('adminpage.getplacement.id');
    Route::post('/dashboard/cinema/update',[App\Http\Controllers\CinemaController::class, 'updateCinema'])->name('adminpage.cinema.updates.cinemas');
    Route::post('/dashboard/placement/update',[App\Http\Controllers\CinemaController::class, 'updateplacement'])->name('adminpage.placement.updates');
    
    
    //All Cinema
    Route::get('/dashboard/cinema/', [App\Http\Controllers\CinemaController::class, 'getCinemaPageLoad'])->name('adminpage.cinema.AllCinemas');
    Route::post('/dashboard/addcinema', [App\Http\Controllers\CinemaController::class, 'saveCinema'])->name('admin.save_cinema');

    //Distributor Manager
    Route::get('/distributor/add/movie', [App\Http\Controllers\DistributorController::class, 'getMoviePageLoad'])->name('distributor.add.movie');
    Route::get('/distributor/movie/all',[App\Http\Controllers\DistributorController::class, 'getMovieTablePageLoad'])->name('distributor.movie.all');
    Route::get('/distributor/movie/{id}/edit',[App\Http\Controllers\DistributorController::class, 'getEditMoviePage'])->name('distributor.movie.edit');
    Route::get('/distributor/request/edit/{id}/placement',[App\Http\Controllers\DistributorController::class, 'editPlacement'])->name('distributor.edit.placement');
    Route::get('/distributor/bookings/calendar',[App\Http\Controllers\DistributorController::class, 'showCalendar'])->name('distributor.show.showCalendar');

    //Booking Calendar
    Route::get('bookings/calendar/view',[App\Http\Controllers\BookingsController::class, 'getCalendarPageload'])->name('bookigs.calendar.view');
    Route::get('bookings/duration/view',[App\Http\Controllers\BookingsController::class, 'showDuration'])->name('bookigs.duration.view');
    Route::post('/duration/update/{id}',[App\Http\Controllers\BookingsController::class, 'UpdateDeadline'])->name('bookigs.duration.update');

});




