<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $filaments = Category::updateOrCreate(
            ['slug' => 'filaments'],
            [
                'parent_id' => null,
                'name' => 'Filaments',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'pla'],
            [
                'parent_id' => $filaments->id,
                'name' => 'PLA',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'petg'],
            [
                'parent_id' => $filaments->id,
                'name' => 'PETG',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'asa'],
            [
                'parent_id' => $filaments->id,
                'name' => 'ASA',
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'abs'],
            [
                'parent_id' => $filaments->id,
                'name' => 'ABS',
                'is_active' => true,
                'sort_order' => 4,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'nylon'],
            [
                'parent_id' => $filaments->id,
                'name' => 'NYLON',
                'is_active' => true,
                'sort_order' => 5,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'tpu'],
            [
                'parent_id' => $filaments->id,
                'name' => 'TPU',
                'is_active' => true,
                'sort_order' => 6,
            ]
        );

        $resins = Category::updateOrCreate(
            ['slug' => 'resins'],
            [
                'parent_id' => null,
                'name' => 'Resins',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'standard'],
            [
                'parent_id' => $resins->id,
                'name' => 'Standard',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'tough'],
            [
                'parent_id' => $resins->id,
                'name' => 'Tough',
                'is_active' => true,
                'sort_order' => 2,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'printers'],
            [
                'parent_id' => null,
                'name' => 'Printers',
                'is_active' => true,
                'sort_order' => 3,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'accessories'],
            [
                'parent_id' => null,
                'name' => 'Accessories',
                'is_active' => true,
                'sort_order' => 4,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'tools'],
            [
                'parent_id' => null,
                'name' => 'Tools',
                'is_active' => true,
                'sort_order' => 5,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'brands'],
            [
                'parent_id' => null,
                'name' => 'Brands',
                'is_active' => true,
                'sort_order' => 6,
            ]
        );

        Category::updateOrCreate(
            ['slug' => 'sale'],
            [
                'parent_id' => null,
                'name' => 'Sale',
                'is_active' => true,
                'sort_order' => 7,
            ]
        );
    }
}