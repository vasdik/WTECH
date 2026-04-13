<?php

namespace Database\Seeders;

use App\Models\enum\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Charcoal Black', 'slug' => 'charcoal-black', 'hex_code' => '#2B2B2B', 'sort_order' => 1],
            ['name' => 'White', 'slug' => 'white', 'hex_code' => '#F5F5F5', 'sort_order' => 2],
            ['name' => 'Beige', 'slug' => 'beige', 'hex_code' => '#D8C3A5', 'sort_order' => 3],
            ['name' => 'Red', 'slug' => 'red', 'hex_code' => '#C0392B', 'sort_order' => 4],
            ['name' => 'Blue', 'slug' => 'blue', 'hex_code' => '#2980B9', 'sort_order' => 5],
            ['name' => 'Grey', 'slug' => 'grey', 'hex_code' => '#7F8C8D', 'sort_order' => 6],
            ['name' => 'Green', 'slug' => 'green', 'hex_code' => '#2E8B57', 'sort_order' => 7],
            ['name' => 'Gold', 'slug' => 'gold', 'hex_code' => '#D4AF37', 'sort_order' => 8],
            ['name' => 'Salmon', 'slug' => 'salmon', 'hex_code' => '#FA8072', 'sort_order' => 9],
            ['name' => 'Orange', 'slug' => 'orange', 'hex_code' => '#F39C12', 'sort_order' => 10],
            ['name' => 'Yellow', 'slug' => 'yellow', 'hex_code' => '#F4D03F', 'sort_order' => 11],
        ];

        foreach ($colors as $color) {
            Color::updateOrCreate(
                ['slug' => $color['slug']],
                $color
            );
        }
    }
}