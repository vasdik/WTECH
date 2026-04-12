<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\FilamentDetail;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\enum\Color;
use App\Models\enum\Diameter;
use App\Models\enum\FilamentType;
use App\Models\enum\Weight;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $plaCategory = Category::where('slug', 'pla')->firstOrFail();
            $petgCategory = Category::where('slug', 'petg')->firstOrFail();
            $standardResinCategory = Category::where('slug', 'standard')->firstOrFail();
            $printersCategory = Category::where('slug', 'printers')->firstOrFail();
            $toolsCategory = Category::where('slug', 'tools')->firstOrFail();

            $colorBlack = Color::where('slug', 'charcoal-black')->firstOrFail();
            $colorWhite = Color::where('slug', 'white')->firstOrFail();
            $colorBeige = Color::where('slug', 'beige')->firstOrFail();
            $colorGrey = Color::where('slug', 'grey')->firstOrFail();

            $weight1000 = Weight::where('grams', 1000)->firstOrFail();
            $diameter175 = Diameter::where('mm_value', 1.75)->firstOrFail();

            $plaType = FilamentType::where('slug', 'pla')->firstOrFail();
            $petgType = FilamentType::where('slug', 'petg')->firstOrFail();

            $polyterra = Product::updateOrCreate(
                ['slug' => 'polyterra-pla-charcoal-black'],
                [
                    'category_id' => $plaCategory->id,
                    'name' => 'PolyTerra PLA Charcoal Black, 1,75 mm / 1000 g',
                    'brand' => 'Polymaker',
                    'short_description' => 'Bioplastic PLA filament with reduced plastic content.',
                    'description' => 'PolyTerra PLA is a bioplastic filament for 3D printing in which organic materials are combined with PLA to reduce the plastic content and create a more environmentally friendly filament.',
                    'price_gross' => 15.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.50,
                    'rating_count' => 249,
                    'stock_qty' => 20,
                    'is_active' => true,
                ]
            );

            FilamentDetail::updateOrCreate(
                ['product_id' => $polyterra->id],
                [
                    'filament_type_id' => $plaType->id,
                    'recommended_nozzle_temp_min' => 190,
                    'recommended_nozzle_temp_max' => 230,
                    'recommended_bed_temp_min' => 25,
                    'recommended_bed_temp_max' => 60,
                    'material_note' => 'Easy processing, good rigidity, reduced plastic content.',
                ]
            );

            $polyterraBlack = ProductVariant::updateOrCreate(
                ['slug' => 'polyterra-pla-charcoal-black-175-1000'],
                [
                    'product_id' => $polyterra->id,
                    'sku' => 'POLYTERRA-BLK-175-1000',
                    'color_id' => $colorBlack->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'price_gross' => 15.99,
                    'stock_qty' => 10,
                    'is_default' => true,
                    'is_active' => true,
                ]
            );

            $polyterraWhite = ProductVariant::updateOrCreate(
                ['slug' => 'polyterra-pla-white-175-1000'],
                [
                    'product_id' => $polyterra->id,
                    'sku' => 'POLYTERRA-WHT-175-1000',
                    'color_id' => $colorWhite->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'price_gross' => 15.99,
                    'stock_qty' => 6,
                    'is_default' => false,
                    'is_active' => true,
                ]
            );

            $polyterraBeige = ProductVariant::updateOrCreate(
                ['slug' => 'polyterra-pla-beige-175-1000'],
                [
                    'product_id' => $polyterra->id,
                    'sku' => 'POLYTERRA-BEI-175-1000',
                    'color_id' => $colorBeige->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'price_gross' => 15.99,
                    'stock_qty' => 4,
                    'is_default' => false,
                    'is_active' => true,
                ]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $polyterra->id, 'path' => 'images/products/Polyterra_PLA/polyterra_PLA_Black_1_.512x512.avif'],
                [
                    'product_variant_id' => $polyterraBlack->id,
                    'alt_text' => 'PolyTerra PLA Charcoal Black main image',
                    'sort_order' => 1,
                    'is_primary' => true,
                ]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $polyterra->id, 'path' => 'images/products/Polyterra_PLA/polyterra_PLA_Black_2_.512x512.avif'],
                [
                    'product_variant_id' => $polyterraBlack->id,
                    'alt_text' => 'PolyTerra PLA Charcoal Black side view',
                    'sort_order' => 2,
                    'is_primary' => false,
                ]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $polyterra->id, 'path' => 'images/products/Polyterra_PLA/polyterra_PLA_Black_3_.512x512.avif'],
                [
                    'product_variant_id' => $polyterraBlack->id,
                    'alt_text' => 'PolyTerra PLA Charcoal Black back view',
                    'sort_order' => 3,
                    'is_primary' => false,
                ]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $polyterra->id, 'path' => 'images/products/Polyterra_PLA/polyterra_PLA_Black_4_.512x512.avif'],
                [
                    'product_variant_id' => $polyterraBlack->id,
                    'alt_text' => 'PolyTerra PLA Charcoal Black detail view',
                    'sort_order' => 4,
                    'is_primary' => false,
                ]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $polyterra->id, 'path' => 'images/products/Polyterra_PLA/polyterra_PLA_White_1_.512x512.avif'],
                [
                    'product_variant_id' => $polyterraWhite->id,
                    'alt_text' => 'PolyTerra PLA White',
                    'sort_order' => 5,
                    'is_primary' => false,
                ]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $polyterra->id, 'path' => 'images/products/Polyterra_PLA/polyterra_PLA_Biege_1_.512x512.avif'],
                [
                    'product_variant_id' => $polyterraBeige->id,
                    'alt_text' => 'PolyTerra PLA Beige',
                    'sort_order' => 6,
                    'is_primary' => false,
                ]
            );

            $elegooPla = Product::updateOrCreate(
                ['slug' => 'elegoo-pla-magic-red-blue'],
                [
                    'category_id' => $plaCategory->id,
                    'name' => 'PLA Magic Red&Blue, 1,75 mm / 1000 g',
                    'brand' => 'Elegoo',
                    'short_description' => 'Color-shifting PLA filament.',
                    'description' => 'Decorative PLA filament with magic dual-color effect.',
                    'price_gross' => 38.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.00,
                    'rating_count' => 32,
                    'stock_qty' => 8,
                    'is_active' => true,
                ]
            );

            FilamentDetail::updateOrCreate(
                ['product_id' => $elegooPla->id],
                [
                    'filament_type_id' => $plaType->id,
                    'recommended_nozzle_temp_min' => 190,
                    'recommended_nozzle_temp_max' => 220,
                    'recommended_bed_temp_min' => 50,
                    'recommended_bed_temp_max' => 60,
                    'material_note' => 'Decorative PLA with color effect.',
                ]
            );

            ProductVariant::updateOrCreate(
                ['slug' => 'elegoo-pla-magic-red-blue-175-1000'],
                [
                    'product_id' => $elegooPla->id,
                    'sku' => 'ELEGOO-PLA-MAG-175-1000',
                    'color_id' => $colorGrey->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'price_gross' => 38.99,
                    'stock_qty' => 8,
                    'is_default' => true,
                    'is_active' => true,
                ]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $elegooPla->id, 'path' => 'images/products/Elegoo_PLA_Magic/elegoo_PLA_Black_Purple_1_512x512.avif'],
                [
                    'product_variant_id' => null,
                    'alt_text' => 'Elegoo PLA Magic',
                    'sort_order' => 1,
                    'is_primary' => true,
                ]
            );

            $esunPla = Product::updateOrCreate(
                ['slug' => 'esun-pla-black'],
                [
                    'category_id' => $plaCategory->id,
                    'name' => 'PLA Black, 1,75 mm / 1000 g',
                    'brand' => 'eSUN',
                    'short_description' => 'Classic black PLA filament.',
                    'description' => 'Reliable PLA filament for everyday printing.',
                    'price_gross' => 15.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 1.70,
                    'rating_count' => 12,
                    'stock_qty' => 15,
                    'is_active' => true,
                ]
            );

            FilamentDetail::updateOrCreate(
                ['product_id' => $esunPla->id],
                [
                    'filament_type_id' => $plaType->id,
                    'recommended_nozzle_temp_min' => 200,
                    'recommended_nozzle_temp_max' => 230,
                    'recommended_bed_temp_min' => 50,
                    'recommended_bed_temp_max' => 60,
                    'material_note' => 'Standard PLA for general use.',
                ]
            );

            ProductVariant::updateOrCreate(
                ['slug' => 'esun-pla-black-175-1000'],
                [
                    'product_id' => $esunPla->id,
                    'sku' => 'ESUN-PLA-BLK-175-1000',
                    'color_id' => $colorBlack->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'price_gross' => 15.99,
                    'stock_qty' => 15,
                    'is_default' => true,
                    'is_active' => true,
                ]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $esunPla->id, 'path' => 'images/products/eSun_PLA/esun_PLA_Black_1_.512x512.avif'],
                [
                    'product_variant_id' => null,
                    'alt_text' => 'eSUN PLA Black',
                    'sort_order' => 1,
                    'is_primary' => true,
                ]
            );

            $prusamentPetg = Product::updateOrCreate(
                ['slug' => 'prusament-petg-clear'],
                [
                    'category_id' => $petgCategory->id,
                    'name' => 'PETG Clear, 1,75 mm / 1000 g',
                    'brand' => 'Prusament',
                    'short_description' => 'Transparent PETG filament.',
                    'description' => 'PETG with good mechanical resistance and clean finish.',
                    'price_gross' => 24.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.40,
                    'rating_count' => 28,
                    'stock_qty' => 7,
                    'is_active' => true,
                ]
            );

            FilamentDetail::updateOrCreate(
                ['product_id' => $prusamentPetg->id],
                [
                    'filament_type_id' => $petgType->id,
                    'recommended_nozzle_temp_min' => 230,
                    'recommended_nozzle_temp_max' => 250,
                    'recommended_bed_temp_min' => 70,
                    'recommended_bed_temp_max' => 90,
                    'material_note' => 'PETG filament with good strength and chemical resistance.',
                ]
            );

            ProductVariant::updateOrCreate(
                ['slug' => 'prusament-petg-clear-175-1000'],
                [
                    'product_id' => $prusamentPetg->id,
                    'sku' => 'PRUSA-PETG-CLR-175-1000',
                    'color_id' => $colorWhite->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'price_gross' => 24.99,
                    'stock_qty' => 7,
                    'is_default' => true,
                    'is_active' => true,
                ]
            );

            $resin = Product::updateOrCreate(
                ['slug' => 'elegoo-standard-resin-grey'],
                [
                    'category_id' => $standardResinCategory->id,
                    'name' => 'Standard Resin Grey, 1000 g',
                    'brand' => 'Elegoo',
                    'short_description' => 'Standard grey resin for LCD printers.',
                    'description' => 'Standard resin suitable for everyday resin printing.',
                    'price_gross' => 21.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.20,
                    'rating_count' => 18,
                    'stock_qty' => 11,
                    'is_active' => true,
                ]
            );

            Product::updateOrCreate(
                ['slug' => 'bambulab-a1-mini-combo'],
                [
                    'category_id' => $printersCategory->id,
                    'name' => 'A1 Mini Combo',
                    'brand' => 'Bambulab',
                    'short_description' => 'Compact desktop FDM printer.',
                    'description' => 'Compact 3D printer suitable for home and enthusiast use.',
                    'price_gross' => 489.00,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.80,
                    'rating_count' => 41,
                    'stock_qty' => 3,
                    'is_active' => true,
                ]
            );

            Product::updateOrCreate(
                ['slug' => 'flush-cutters'],
                [
                    'category_id' => $toolsCategory->id,
                    'name' => 'Flush Cutters',
                    'brand' => 'Generic',
                    'short_description' => 'Basic flush cutters for print cleanup.',
                    'description' => 'Useful tool for removing supports and trimming filament.',
                    'price_gross' => 6.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.10,
                    'rating_count' => 9,
                    'stock_qty' => 25,
                    'is_active' => true,
                ]
            );
        });
    }
}