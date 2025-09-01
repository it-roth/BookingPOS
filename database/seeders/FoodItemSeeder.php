<?php

namespace Database\Seeders;

use App\Models\FoodItem;
use Illuminate\Database\Seeder;

class FoodItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $foodItems = [
            [
                'name' => 'Popcorn - Large',
                'description' => 'Freshly popped buttery popcorn in a large serving, perfect for sharing during a movie.',
                'price' => 7.99,
                'image_url' => 'https://images.unsplash.com/photo-1585647347384-2542c9f859cb?w=600',
                'is_available' => true,
                'category' => 'Snacks',
                'preparation_time' => 3
            ],
            [
                'name' => 'Nachos with Cheese',
                'description' => 'Crispy corn tortilla chips served with warm melted cheese sauce.',
                'price' => 6.50,
                'image_url' => 'https://images.unsplash.com/photo-1582169296194-e4d644c48063?w=600',
                'is_available' => true,
                'category' => 'Snacks',
                'preparation_time' => 5
            ],
            [
                'name' => 'Chicken Tenders & Fries',
                'description' => 'Juicy chicken tenders served with a side of crispy french fries and dipping sauce.',
                'price' => 10.99,
                'image_url' => 'https://images.unsplash.com/photo-1619881599643-e36f85c2c85a?w=600',
                'is_available' => true,
                'category' => 'Fast Food',
                'preparation_time' => 10
            ],
            [
                'name' => 'Movie Combo Pack',
                'description' => 'Large popcorn, 2 regular drinks, and a candy of your choice. Perfect for couples!',
                'price' => 15.99,
                'image_url' => 'https://images.unsplash.com/photo-1613581099455-1a5d4998d2aa?w=600',
                'is_available' => true,
                'category' => 'Combos',
                'preparation_time' => 5
            ],
            [
                'name' => 'Chocolate Brownie Sundae',
                'description' => 'Warm chocolate brownie topped with vanilla ice cream, chocolate sauce, and whipped cream.',
                'price' => 8.99,
                'image_url' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?w=600',
                'is_available' => true,
                'category' => 'Desserts',
                'preparation_time' => 7
            ],
        ];

        foreach ($foodItems as $foodItem) {
            FoodItem::create($foodItem);
        }
    }
} 