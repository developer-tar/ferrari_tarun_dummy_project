<?php

namespace Database\Factories;

use App\Models\Tuner;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TunerFactory extends Factory
{
    protected $model = Tuner::class;

    public function definition(): array
    {
        return [
            'nickname' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'profession' => $this->faker->jobTitle(),
            'birth_date' => $this->faker->date(),
            'bio' => $this->faker->text(),
            'remember_token' => Str::random(10),
        ];
    }
}