<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\Tuner;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        ResetPassword::createUrlUsing(function (Tuner $tuner, string $token) {
            return config('app.reset_password_frontend_url')
                . '?token=' . $token
                . '&email=' . urlencode($tuner->email);
        });
    }
}
