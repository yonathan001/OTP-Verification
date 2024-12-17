<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OtpController;


Route::get('/otp', function () {
    return view('otp');
});
Route::post('/send-otp', [OtpController::class, 'sendOtp']);
Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);