<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    public function registerCustomer(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRole::Customer->value,
        ]);
    }

    public function generateOtp(string $email): string
    {
        $otp = (string) rand(100000, 999999);

        Cache::put("otp_{$email}", $otp, now()->addMinutes(10));

        // In a real application, you would send this via Mail or SMS
        // Mail::to($email)->send(new OtpMail($otp));

        return $otp; // Returning for testing purposes in dev
    }

    public function verifyOtp(string $email, string $otp): bool
    {
        $storedOtp = Cache::get("otp_{$email}");

        if ($storedOtp && $storedOtp === $otp) {
            Cache::forget("otp_{$email}");

            return true;
        }

        return false;
    }
}
