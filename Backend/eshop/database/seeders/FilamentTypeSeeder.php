<?php

namespace Database\Seeders;

use App\Models\enum\FilamentType;
use Illuminate\Database\Seeder;

class FilamentTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'PLA', 'slug' => 'pla', 'sort_order' => 1],
            ['name' => 'PETG', 'slug' => 'petg', 'sort_order' => 2],
            ['name' => 'ASA', 'slug' => 'asa', 'sort_order' => 3],
            ['name' => 'ABS', 'slug' => 'abs', 'sort_order' => 4],
            ['name' => 'NYLON', 'slug' => 'nylon', 'sort_order' => 5],
            ['name' => 'TPU', 'slug' => 'tpu', 'sort_order' => 6],
        ];

        foreach ($types as $type) {
            FilamentType::updateOrCreate(
                ['slug' => $type['slug']],
                $type
            );
        }
    }
}