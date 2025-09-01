<?php

namespace Database\Seeders;

use App\Models\Drink;
use Illuminate\Database\Seeder;

class DrinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drinks = [
            [
                'name' => 'Cola',
                'description' => 'Classic cola beverage, served with ice.',
                'price' => 3.99,
                'image_url' => 'https://images.unsplash.com/photo-1622483767028-3f66f32aef97?w=600',
                'is_available' => true,
                'category' => 'Soft Drinks',
                'size' => 'regular'
            ],
            [
                'name' => 'Orange Juice',
                'description' => 'Freshly squeezed orange juice, no added sugar.',
                'price' => 4.50,
                'image_url' => 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=600',
                'is_available' => true,
                'category' => 'Juices',
                'size' => 'regular'
            ],
            [
                'name' => 'Hot Chocolate',
                'description' => 'Rich and creamy hot chocolate topped with whipped cream.',
                'price' => 4.99,
                'image_url' => 'https://images.unsplash.com/photo-1542990253-0d0f5be5f0ed?w=600',
                'is_available' => true,
                'category' => 'Hot Beverages',
                'size' => 'regular'
            ],
            [
                'name' => 'Bottled Water',
                'description' => 'Pure mineral water in a convenient bottle.',
                'price' => 2.50,
                'image_url' => 'https://images.unsplash.com/photo-1546860255-375ea3da5572?w=600',
                'is_available' => true,
                'category' => 'Water',
                'size' => 'regular'
            ],
            [
                'name' => 'Craft Beer',
                'description' => 'Premium locally brewed craft beer (21+ only).',
                'price' => 6.99,
                'image_url' => 'https://images.unsplash.com/photo-1532634922-8fe0b757fb13?w=600',
                'is_available' => true,
                'category' => 'Alcoholic',
                'size' => 'regular'
            ],
        ];

        foreach ($drinks as $drink) {
            Drink::create($drink);
        }
    }
} 