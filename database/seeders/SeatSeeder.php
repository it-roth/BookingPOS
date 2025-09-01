<?php

namespace Database\Seeders;

use App\Models\Seat;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Grand Theater hall has ID 1
        $hallId = 1;
        
        // Create seats in rows A-D, with 10 seats per row (1-10)
        $rows = ['A', 'B', 'C', 'D'];
        
        foreach ($rows as $rowIndex => $row) {
            for ($seatNumber = 1; $seatNumber <= 10; $seatNumber++) {
                $seatType = 'regular';
                
                // Make front row (A) Premium
                if ($row === 'A') {
                    $seatType = 'premium';
                }
                
                // Make center seats of rows C and D VIP
                if (in_array($row, ['C', 'D']) && $seatNumber >= 4 && $seatNumber <= 7) {
                    $seatType = 'vip';
                }
                
                $additionalCharge = 0;
                if ($seatType === 'premium') {
                    $additionalCharge = 3.00;
                } elseif ($seatType === 'vip') {
                    $additionalCharge = 5.00;
                }
                
                Seat::create([
                    'hall_id' => $hallId,
                    'row' => $row,
                    'number' => $seatNumber,
                    'type' => $seatType,
                    'additional_charge' => $additionalCharge,
                    'is_available' => true
                ]);
            }
        }
    }
} 