<?php

use App\Http\Controllers\AppointmentController;
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

Route::get('/get-appointmets', function () {
    return view('welcome');
});

Route::prefix('appointments')->group(function () {
    Route::get('/all', [AppointmentController::class,'index'])->name('get-all-appoinements-web');
    Route::get('/upcoming', [AppointmentController::class,'upcoming'])->name('get-upcoming-appoinements-web');
});