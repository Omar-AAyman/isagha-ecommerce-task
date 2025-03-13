<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::insert([
            [
                'name' => 'Gold Ring',
                'description' => 'Elegant gold ring with diamond.',
                'price' => 1500.00,
                'image' => 'gold-ring.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Silver Necklace',
                'description' => 'Stylish silver necklace for daily wear.',
                'price' => 500.00,
                'image' => 'silver-necklace.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Diamond Bracelet',
                'description' => 'Luxury diamond bracelet.',
                'price' => 3500.00,
                'image' => 'diamond-bracelet.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
