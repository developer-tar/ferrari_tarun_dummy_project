<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Translation\HasLocalePreference;
use App\Notifications\ResetPassword;

class Tuner extends Authenticatable implements HasLocalePreference {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nickname',
        'slug',
        'email',
        'password',
        'first_name',
        'last_name',
        'language',
        'profession',
        'birth_date',
        'bio',
        'profile_photo_path',
        'google_id',
        'facebook_id',
        'portale',
        'is_premium'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    public function preferredLocale(): string {
        return $this->locale ?? config('app.locale');
    }

    public function sendPasswordResetNotification($token) {
        $this->notify(new ResetPassword($token, $this->email));
    }
}
