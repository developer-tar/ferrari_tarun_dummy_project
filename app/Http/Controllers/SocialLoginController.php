<?php

namespace App\Http\Controllers;

use App\Models\Tuner;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller {
    public function redirectToGoogle() {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback() {
        try {
             $googleUser = Socialite::driver('google')->stateless()->user();
             return $this->handleSocialLogin($googleUser, 'google');
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Login failed: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function redirectToFacebook() {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleFacebookCallback() {
        $user = Socialite::driver('facebook')->stateless()->user();
        return $this->handleSocialLogin($user, 'facebook');
    }

    public function redirectToInstagram() {
        return Socialite::driver('instagram')->stateless()->redirect();
    }

    public function handleInstagramCallback() {
        $user = Socialite::driver('instagram')->stateless()->user();
        return $this->handleSocialLogin($user, 'instagram');
    }

    protected function handleSocialLogin($socialUser, $provider) {
        $tuner = Tuner::where("{$provider}_id", $socialUser->getId())
            ->orWhere('email', $socialUser->getEmail())
            ->first();

        if (!$tuner) {
            $tuner = Tuner::create([
                "{$provider}_id" => $socialUser->getId(),
                'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'Unknown',
                'email' => $socialUser->getEmail(),
                'nickname' => Str::slug($socialUser->getName() ?? 'tuner') . '-' . uniqid(),
                'profile_photo_path' => $socialUser->getAvatar(),
                'password' => Hash::make(Str::random(16)), 
                'email_verified_at' => Carbon::now(),
            ]);
        } else {
            $tuner->update([
                "{$provider}_id" => $socialUser->getId(),
                'profile_photo_path' => $socialUser->getAvatar(),
                'email_verified_at' => Carbon::now(),
            ]);
        }

        $token = $tuner->createToken('tuner_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'tuner' => $tuner,
        ]);
    }
}
