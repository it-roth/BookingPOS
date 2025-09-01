<?php

namespace Database\Seeders;

use App\Models\Hall;
use Illuminate\Database\Seeder;

class HallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $halls = [
            [
                'name' => 'Grand Theater',
                'capacity' => 50,
                'hall_type' => 'Standard',
                'is_active' => true,
                'description' => 'Our largest cinema hall with comfortable seating and state-of-the-art sound system.'
            ],
        ];

        foreach ($halls as $hall) {
            Hall::create($hall);
        }
    }
} 