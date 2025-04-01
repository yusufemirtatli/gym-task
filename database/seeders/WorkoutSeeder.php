<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('workouts')->insert([
            [
                'program_id' => 1,
                'name' => 'Plank',
                'duration' => 30,
                'calories_burn' => 250,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'program_id' => 1,
                'name' => 'Cardio',
                'duration' => 45,
                'calories_burn' => 400,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'program_id' => 1,
                'name' => 'Power',
                'duration' => 20,
                'calories_burn' => 150,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
