<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\OTP;
use Carbon\Carbon;

class OtpController extends Controller
{
    // Function to send OTP
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        // Save OTP to the database
        OTP::updateOrCreate(
            ['email' => $request->email],
            ['otp' => $otp, 'expires_at' => $expiresAt]
        );

        // Send OTP to the user's email
        Mail::raw("Your OTP is: $otp", function ($message) use ($request) {
            $message->to($request->email)
                    ->subject('Your OTP Verification Code');
        });

        return response()->json(['message' => 'OTP sent successfully.']);
    }

    // Function to verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $otpRecord = OTP::where('email', $request->email)->first();

        if (!$otpRecord || $otpRecord->otp !== $request->otp || $otpRecord->expires_at < Carbon::now()) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 400);
        }

        // OTP is valid, delete the record
        $otpRecord->delete();

        return response()->json(['message' => 'OTP verified successfully.']);
    }
}
