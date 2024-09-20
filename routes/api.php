<?php

use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\auth\BookingController;
use App\Http\Controllers\api\dashboard\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function() {
    Route::post('login','login');
    Route::post('register','register');
});

Route::controller(DashboardController::class)->group(function() {
    Route::get('dashboard', 'index');
    Route::get('show-room/{id}', 'showRoom');
});

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::controller(BookingController::class)->group(function() {
        Route::post('store-booking/{roomId}', 'storeBooking');
        Route::get('view-bookings', 'viewBookings');
    });
});

