<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Config::factory()->create([
            'age_range' => '18-30,31-40,41-50,51-60,61-70',
            'load' => '0.6,0.7,0.8,0.9,1',
            'fixed_rate' => 3
        ]);
    }
}
