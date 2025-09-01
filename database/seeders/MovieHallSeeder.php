<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use App\Models\Hall;
use App\Models\MovieHall;
use Carbon\Carbon;

class MovieHallSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get movies and halls
        $movies = Movie::all();
        $halls = Hall::all();
        
        if ($movies->isEmpty() || $halls->isEmpty()) {
            $this->command->info('No movies or halls found. Please run movie and hall seeders first.');
            return;
        }
        
        // Clear existing movie hall data
        MovieHall::truncate();
        
        // Generate showtimes for the next 7 days
        $startDate = Carbon::now()->startOfDay();
        $endDate = Carbon::now()->addDays(7)->endOfDay();
        
        // Showtime slots
        $timeSlots = [
            '10:00', '13:00', '16:00', '19:00', '22:00'
        ];
        
        // Create specific movie-hall assignments
        $assignments = [];
        
        // If we have at least 3 movies and 3 halls
        if ($movies->count() >= 3 && $halls->count() >= 3) {
            // Movie 1 in Hall 1 exclusively
            $assignments[] = [
                'movie' => $movies[0],
                'halls' => [$halls[0]],
                'exclusive' => true
            ];
            
            // Movie 2 in Hall 2 exclusively
            $assignments[] = [
                'movie' => $movies[1],
                'halls' => [$halls[1]],
                'exclusive' => true
            ];
            
            // Movie 3 in Hall 3 exclusively
            $assignments[] = [
                'movie' => $movies[2],
                'halls' => [$halls[2]],
                'exclusive' => true
            ];
            
            // Any remaining movies in random halls (non-exclusive)
            for ($i = 3; $i < $movies->count(); $i++) {
                $randomHalls = $halls->random(min(2, $halls->count()))->all();
                $assignments[] = [
                    'movie' => $movies[$i],
                    'halls' => $randomHalls,
                    'exclusive' => false
                ];
            }
        } else {
            // If we don't have enough movies or halls, manually assign what we have
            foreach ($movies as $index => $movie) {
                // Assign each movie to a specific hall if possible, otherwise wrap around
                $hallIndex = $index % $halls->count();
                $assignments[] = [
                    'movie' => $movie,
                    'halls' => [$halls[$hallIndex]],
                    'exclusive' => true
                ];
            }
        }
        
        $this->command->info('Creating movie-hall assignments...');
        
        // Process the assignments
        foreach ($assignments as $assignment) {
            $movie = $assignment['movie'];
            $selectedHalls = $assignment['halls'];
            
            foreach ($selectedHalls as $hall) {
                // For each day in the next 7 days
                $currentDate = clone $startDate;
                
                while ($currentDate <= $endDate) {
                    // Randomly pick 2-3 time slots for each day
                    $selectedTimeSlots = collect($timeSlots)->random(rand(2, 3))->sort()->values();
                    
                    foreach ($selectedTimeSlots as $timeSlot) {
                        $showtime = Carbon::parse($currentDate->format('Y-m-d') . ' ' . $timeSlot);
                        
                        // Create movie hall entry
                        MovieHall::create([
                            'movie_id' => $movie->id,
                            'hall_id' => $hall->id,
                            'showtime' => $showtime,
                            'ticket_price' => 0, // No base price, only using seat prices
                            'is_active' => true
                        ]);
                    }
                    
                    $currentDate->addDay();
                }
                
                $this->command->info("Assigned '{$movie->title}' to '{$hall->name}'");
            }
        }
        
        $this->command->info('Movie hall data seeded successfully!');
    }
}
