<?php

namespace Database\Seeders;

use App\Models\enum\Weight;
use Illuminate\Database\Seeder;

class WeightSeeder extends Seeder
{
    public function run(): void
    {
        $weights = [
            ['label' => '250 g', 'grams' => 250, 'sort_order' => 1],
            ['label' => '500 g', 'grams' => 500, 'sort_order' => 2],
            ['label' => '1000 g', 'grams' => 1000, 'sort_order' => 3],
            ['label' => '5000 g', 'grams' => 5000, 'sort_order' => 4],
        ];

        foreach ($weights as $weight) {
            Weight::updateOrCreate(
                ['label' => $weight['label'], 'grams' => $weight['grams']],
                $weight
            );
        }
    }
}