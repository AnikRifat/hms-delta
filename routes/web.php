<?php

use App\Http\Controllers\Backend\AdminsController;
use App\Http\Controllers\Backend\AppointmentScheduleController;
use App\Http\Controllers\Backend\Auth\ForgotPasswordController;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\BookingController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DepartmentController;
use App\Http\Controllers\Backend\DoctorController;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

// Route::get('/', 'HomeController@redirectAdmin')->name('index');
Route::get('/', 'HomeController@index')->name('home');

/**
 * Admin routes
 */
route::get('appointment-schedules/by-doctor', [AppointmentScheduleController::class, 'getByDoctor'])->name('appointmentSchedules.byDoctor');

route::get('doctors/by-department', [AppointmentScheduleController::class, 'getDoctors'])->name('doctors.byDepartment');
Route::post('/store/bookings', [HomeController::class, 'storeBooking'])->name('bookings.store');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RolesController::class);
    Route::resource('admins', AdminsController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('doctors', DoctorController::class);
    Route::resource('bookings', BookingController::class);

    // Login Routes.
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login/submit', [LoginController::class, 'login'])->name('login.submit');

    // Logout Routes.
    Route::post('/logout/submit', [LoginController::class, 'logout'])->name('logout.submit');

    // Forget Password Routes.
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/reset/submit', [ForgotPasswordController::class, 'reset'])->name('password.update');
})->middleware('auth:admin');
