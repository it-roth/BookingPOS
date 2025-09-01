<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $movies = [
            [
                'title' => 'The Matrix Resurrections',
                'description' => 'Neo finds himself back in the Matrix and must fight once again for freedom.',
                'genre' => 'Sci-Fi/Action',
                'duration' => 148,
                'image_url' => 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=600',
                'release_date' => '2023-01-15',
                'is_showing' => true,
                'price' => 12.99
            ],
            [
                'title' => 'Eternal Sunshine',
                'description' => 'A heartwarming story about memories, love, and the connections that bind us together.',
                'genre' => 'Romance/Drama',
                'duration' => 124,
                'image_url' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=600',
                'release_date' => '2023-02-10',
                'is_showing' => true,
                'price' => 11.99
            ],
            [
                'title' => 'Galactic Thunder',
                'description' => 'Epic space adventure following the crew of a rogue starship as they battle intergalactic threats.',
                'genre' => 'Sci-Fi/Adventure',
                'duration' => 155,
                'image_url' => 'https://images.unsplash.com/photo-1518066000714-58c45f1a2c0a?w=600',
                'release_date' => '2023-03-05',
                'is_showing' => true,
                'price' => 13.99
            ],
            [
                'title' => 'Whispers in the Dark',
                'description' => 'A psychological thriller about a detective who discovers supernatural elements in a serial killer case.',
                'genre' => 'Thriller/Horror',
                'duration' => 132,
                'image_url' => 'https://images.unsplash.com/photo-1559583109-3e7968136c99?w=600',
                'release_date' => '2023-02-25',
                'is_showing' => true,
                'price' => 12.50
            ],
            [
                'title' => 'Summer of 99',
                'description' => 'Coming-of-age comedy about a group of friends enjoying their last summer together before college.',
                'genre' => 'Comedy',
                'duration' => 118,
                'image_url' => 'https://images.unsplash.com/photo-1625353079916-3df6c8f15ce1?w=600',
                'release_date' => '2023-01-28',
                'is_showing' => true,
                'price' => 10.99
            ],
        ];

        foreach ($movies as $movie) {
            Movie::create($movie);
        }
    }
} 