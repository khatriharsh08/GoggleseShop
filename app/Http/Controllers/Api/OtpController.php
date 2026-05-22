<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    public function __construct(public AuthService $authService) {}

    public function sendOtp(Request $request): JsonResponse
    {
        $request->validate(['email' => ['required', 'email']]);
        $otp = $this->authService->generateOtp($request->email);

        return response()->json(['message' => 'OTP sent successfully', 'preview_otp' => $otp]);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate(['email' => ['required', 'email'], 'otp' => ['required']]);
        if ($this->authService->verifyOtp($request->email, $request->otp)) {
            return response()->json(['message' => 'OTP verified successfully']);
        }

return response()->json(['message' => 'Invalid or expired OTP'], 400);
    }
}
