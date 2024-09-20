<?php

use App\Http\Controllers\admin\booking\BookingController;
use App\Http\Controllers\admin\dashboard\DashboardController;
use App\Http\Controllers\admin\hotel\HotelController;
use App\Http\Controllers\admin\room\RoomController;
use App\Http\Controllers\auth\AuthController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'admin', 'as' => 'admin.'],function () {
    Route::controller(AuthController::class)->group(function() {
        Route::get('/', 'loginForm')->name('login.form');
        Route::post('/', 'login')->name('login');

        Route::group(['middleware' => 'auth'], function() {
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

            Route::resource('hotels', HotelController::class);
            Route::resource('rooms', RoomController::class);
            Route::resource('bookings', BookingController::class)->only(['index']);
        });

        
    });
    
});


Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');

