<?php

namespace Database\Seeders;

use App\Models\Tuner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TunerSeeder extends Seeder
{
    public function run(): void
    {
        // Truncate the tuners table
        DB::table('tuners')->truncate();

        Tuner::factory()->count(10)->create();

        // Create a default test tuner
        Tuner::factory()->create([
            'nickname' => 'Test Tuner',
            'email' => 'test.tuner@example.com',
            'password' => bcrypt('password'),
            'first_name' => 'Test',
            'last_name' => 'Tuner',
            'profession' => 'Master Tuner',
            'birth_date' => '1990-01-01',
            'bio' => 'I am a test tuner account',
        ]);
    }
}