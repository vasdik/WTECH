<?php

namespace Database\Seeders;

use App\Models\enum\Diameter;
use Illuminate\Database\Seeder;

class DiameterSeeder extends Seeder
{
    public function run(): void
    {
        $diameters = [
            ['label' => '1.75 mm', 'mm_value' => 1.75, 'sort_order' => 1],
            ['label' => '2.85 mm', 'mm_value' => 2.85, 'sort_order' => 2],
        ];

        foreach ($diameters as $diameter) {
            Diameter::updateOrCreate(
                ['label' => $diameter['label'], 'mm_value' => $diameter['mm_value']],
                $diameter
            );
        }
    }
}