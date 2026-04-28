<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\FilamentDetail;
use App\Models\Product;
use App\Models\ProductFamily;
use App\Models\ProductImage;
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
            $accessoriesCategory = Category::where('slug', 'accessories')->firstOrFail();

            $colorBlack = Color::where('slug', 'charcoal-black')->firstOrFail();
            $colorWhite = Color::where('slug', 'white')->firstOrFail();
            $colorBeige = Color::where('slug', 'beige')->firstOrFail();
            $colorGrey = Color::where('slug', 'grey')->firstOrFail();
            $colorGold = Color::where('slug', 'gold')->firstOrFail();
            $colorSalmon = Color::where('slug', 'salmon')->firstOrFail();
            $colorOrange = Color::where('slug', 'orange')->firstOrFail();
            $colorYellow = Color::where('slug', 'yellow')->firstOrFail();

            $weight1000 = Weight::where('grams', 1000)->firstOrFail();
            $diameter175 = Diameter::where('mm_value', 1.75)->firstOrFail();

            $plaType = FilamentType::where('slug', 'pla')->firstOrFail();
            $petgType = FilamentType::where('slug', 'petg')->firstOrFail();

            $polyterraFamily = ProductFamily::updateOrCreate(
                ['slug' => 'polyterra-pla'],
                [
                    'category_id' => $plaCategory->id,
                    'name' => 'PolyTerra PLA',
                    'brand' => 'Polymaker',
                    'shared_short_description' => 'Bioplastic PLA filament with reduced plastic content.',
                    'shared_description' => 'PolyTerra PLA is a bioplastic filament for 3D printing in which organic materials are combined with PLA to reduce the plastic content and create a more environmentally friendly filament.',
                    'is_active' => true,
                ]
            );

            $polyterraBlack = Product::updateOrCreate(
                ['slug' => 'polyterra-pla-charcoal-black'],
                [
                    'category_id' => $plaCategory->id,
                    'product_family_id' => $polyterraFamily->id,
                    'color_id' => $colorBlack->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'variant_label' => null,
                    'name' => 'PolyTerra PLA Charcoal Black, 1,75 mm / 1000 g',
                    'brand' => 'Polymaker',
                    'short_description' => 'Bioplastic PLA filament with reduced plastic content.',
                    'description' => 'PolyTerra prints just like PLA and is suitable for everyday use with good rigidity and easy processing.',
                    'price_gross' => 15.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.50,
                    'rating_count' => 249,
                    'stock_qty' => 20,
                    'is_active' => true,
                ]
            );

            $polyterraWhite = Product::updateOrCreate(
                ['slug' => 'polyterra-pla-white'],
                [
                    'category_id' => $plaCategory->id,
                    'product_family_id' => $polyterraFamily->id,
                    'color_id' => $colorWhite->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'variant_label' => null,
                    'name' => 'PolyTerra PLA White, 1,75 mm / 1000 g',
                    'brand' => 'Polymaker',
                    'short_description' => 'Bioplastic PLA filament with reduced plastic content.',
                    'description' => 'PolyTerra prints just like PLA and is suitable for everyday use with good rigidity and easy processing.',
                    'price_gross' => 15.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.40,
                    'rating_count' => 97,
                    'stock_qty' => 9,
                    'is_active' => true,
                ]
            );

            $polyterraBeige = Product::updateOrCreate(
                ['slug' => 'polyterra-pla-beige'],
                [
                    'category_id' => $plaCategory->id,
                    'product_family_id' => $polyterraFamily->id,
                    'color_id' => $colorBeige->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'variant_label' => null,
                    'name' => 'PolyTerra PLA Beige, 1,75 mm / 1000 g',
                    'brand' => 'Polymaker',
                    'short_description' => 'Bioplastic PLA filament with reduced plastic content.',
                    'description' => 'PolyTerra prints just like PLA and is suitable for everyday use with good rigidity and easy processing.',
                    'price_gross' => 15.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.20,
                    'rating_count' => 61,
                    'stock_qty' => 6,
                    'is_active' => true,
                ]
            );

            foreach ([$polyterraBlack, $polyterraWhite, $polyterraBeige] as $product) {
                FilamentDetail::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'filament_type_id' => $plaType->id,
                        'recommended_nozzle_temp_min' => 190,
                        'recommended_nozzle_temp_max' => 230,
                        'recommended_bed_temp_min' => 25,
                        'recommended_bed_temp_max' => 60,
                        'material_note' => 'Easy processing, good rigidity, reduced plastic content.',
                    ]
                );
            }

            ProductImage::updateOrCreate(
                ['product_id' => $polyterraBlack->id, 'path' => 'storage/images/products/Polyterra_PLA/polyterra_PLA_Black_1_.512x512.avif'],
                ['alt_text' => 'PolyTerra PLA Charcoal Black main image', 'sort_order' => 1, 'is_primary' => true]
            );
            ProductImage::updateOrCreate(
                ['product_id' => $polyterraBlack->id, 'path' => 'storage/images/products/Polyterra_PLA/polyterra_PLA_Black_2_.512x512.avif'],
                ['alt_text' => 'PolyTerra PLA Charcoal Black side view', 'sort_order' => 2, 'is_primary' => false]
            );
            ProductImage::updateOrCreate(
                ['product_id' => $polyterraBlack->id, 'path' => 'storage/images/products/Polyterra_PLA/polyterra_PLA_Black_3_.512x512.avif'],
                ['alt_text' => 'PolyTerra PLA Charcoal Black back view', 'sort_order' => 3, 'is_primary' => false]
            );
            ProductImage::updateOrCreate(
                ['product_id' => $polyterraBlack->id, 'path' => 'storage/images/products/Polyterra_PLA/polyterra_PLA_Black_4_.512x512.avif'],
                ['alt_text' => 'PolyTerra PLA Charcoal Black detail view', 'sort_order' => 4, 'is_primary' => false]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $polyterraWhite->id, 'path' => 'storage/images/products/Polyterra_PLA/polyterra_PLA_White_1_.512x512.avif'],
                ['alt_text' => 'PolyTerra PLA White', 'sort_order' => 1, 'is_primary' => true]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $polyterraBeige->id, 'path' => 'storage/images/products/Polyterra_PLA/polyterra_PLA_Biege_1_.512x512.avif'],
                ['alt_text' => 'PolyTerra PLA Beige', 'sort_order' => 1, 'is_primary' => true]
            );

            $elegooPla = Product::updateOrCreate(
                ['slug' => 'elegoo-pla-magic-red-blue'],
                [
                    'category_id' => $plaCategory->id,
                    'product_family_id' => null,
                    'color_id' => $colorGrey->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'variant_label' => null,
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

            ProductImage::updateOrCreate(
                ['product_id' => $elegooPla->id, 'path' => 'storage/images/products/Elegoo_PLA_Magic/elegoo_PLA_Black_Purple_1_512x512.avif'],
                ['alt_text' => 'Elegoo PLA Magic', 'sort_order' => 1, 'is_primary' => true]
            );

            $esunPla = Product::updateOrCreate(
                ['slug' => 'esun-pla-black'],
                [
                    'category_id' => $plaCategory->id,
                    'product_family_id' => null,
                    'color_id' => $colorBlack->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'variant_label' => null,
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

            ProductImage::updateOrCreate(
                ['product_id' => $esunPla->id, 'path' => 'storage/images/products/eSun_PLA/esun_PLA_Black_1_.512x512.avif'],
                ['alt_text' => 'eSUN PLA Black', 'sort_order' => 1, 'is_primary' => true]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $esunPla->id, 'path' => 'storage/images/products/eSun_PLA/esun_PLA_Black_2_.512x512.avif'],
                ['alt_text' => 'eSUN PLA Black side view', 'sort_order' => 2, 'is_primary' => false]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $esunPla->id, 'path' => 'storage/images/products/eSun_PLA/esun_PLA_Black_3_.512x512.avif'],
                ['alt_text' => 'eSUN PLA Black detail view', 'sort_order' => 3, 'is_primary' => false]
            );

                $prusamentPetgFamily = ProductFamily::updateOrCreate(
                ['slug' => 'prusament-petg'],
                [
                    'category_id' => $petgCategory->id,
                    'name' => 'Prusament PETG',
                    'brand' => 'Prusament',
                    'shared_short_description' => 'High-quality PETG filament for durable prints.',
                    'shared_description' => 'Prusament PETG is suitable for strong, dimensionally stable parts with good layer adhesion and chemical resistance.',
                    'is_active' => true,
                ]
            );

            $prusamentPetgClear = Product::updateOrCreate(
                ['slug' => 'prusament-petg-clear'],
                [
                    'category_id' => $petgCategory->id,
                    'product_family_id' => $prusamentPetgFamily->id,
                    'color_id' => $colorWhite->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'variant_label' => null,
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

            $prusamentPetgGold = Product::updateOrCreate(
                ['slug' => 'prusament-petg-gold'],
                [
                    'category_id' => $petgCategory->id,
                    'product_family_id' => $prusamentPetgFamily->id,
                    'color_id' => $colorGold->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'variant_label' => null,
                    'name' => 'PETG Gold, 1,75 mm / 1000 g',
                    'brand' => 'Prusament',
                    'short_description' => 'Gold PETG filament.',
                    'description' => 'Durable PETG filament with good strength and decorative gold finish.',
                    'price_gross' => 26.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.50,
                    'rating_count' => 12,
                    'stock_qty' => 6,
                    'is_active' => true,
                ]
            );

            $prusamentPetgWhite = Product::updateOrCreate(
                ['slug' => 'prusament-petg-white'],
                [
                    'category_id' => $petgCategory->id,
                    'product_family_id' => $prusamentPetgFamily->id,
                    'color_id' => $colorWhite->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'variant_label' => null,
                    'name' => 'PETG White, 1,75 mm / 1000 g',
                    'brand' => 'Prusament',
                    'short_description' => 'White PETG filament.',
                    'description' => 'Durable PETG filament with smooth finish and reliable printability.',
                    'price_gross' => 24.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.60,
                    'rating_count' => 16,
                    'stock_qty' => 9,
                    'is_active' => true,
                ]
            );

            $prusamentPetgSalmon = Product::updateOrCreate(
                ['slug' => 'prusament-petg-salmon'],
                [
                    'category_id' => $petgCategory->id,
                    'product_family_id' => $prusamentPetgFamily->id,
                    'color_id' => $colorSalmon->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'variant_label' => null,
                    'name' => 'PETG Salmon, 1,75 mm / 1000 g',
                    'brand' => 'Prusament',
                    'short_description' => 'Salmon PETG filament.',
                    'description' => 'Durable PETG filament with good layer adhesion and distinctive salmon color.',
                    'price_gross' => 25.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.40,
                    'rating_count' => 10,
                    'stock_qty' => 5,
                    'is_active' => true,
                ]
            );

            $prusamentPetgOrange = Product::updateOrCreate(
                ['slug' => 'prusament-petg-orange'],
                [
                    'category_id' => $petgCategory->id,
                    'product_family_id' => $prusamentPetgFamily->id,
                    'color_id' => $colorOrange->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'variant_label' => null,
                    'name' => 'PETG Orange, 1,75 mm / 1000 g',
                    'brand' => 'Prusament',
                    'short_description' => 'Orange PETG filament.',
                    'description' => 'Durable PETG filament with vivid color and reliable mechanical performance.',
                    'price_gross' => 25.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.30,
                    'rating_count' => 8,
                    'stock_qty' => 5,
                    'is_active' => true,
                ]
            );

            $prusamentPetgYellow = Product::updateOrCreate(
                ['slug' => 'prusament-petg-yellow'],
                [
                    'category_id' => $petgCategory->id,
                    'product_family_id' => $prusamentPetgFamily->id,
                    'color_id' => $colorYellow->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'variant_label' => null,
                    'name' => 'PETG Yellow, 1,75 mm / 1000 g',
                    'brand' => 'Prusament',
                    'short_description' => 'Yellow PETG filament.',
                    'description' => 'Durable PETG filament with bright yellow finish and good print reliability.',
                    'price_gross' => 25.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.20,
                    'rating_count' => 7,
                    'stock_qty' => 4,
                    'is_active' => true,
                ]
            );

            foreach ([
                $prusamentPetgClear,
                $prusamentPetgGold,
                $prusamentPetgWhite,
                $prusamentPetgSalmon,
                $prusamentPetgOrange,
                $prusamentPetgYellow,
            ] as $product) {
                FilamentDetail::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'filament_type_id' => $petgType->id,
                        'recommended_nozzle_temp_min' => 230,
                        'recommended_nozzle_temp_max' => 250,
                        'recommended_bed_temp_min' => 70,
                        'recommended_bed_temp_max' => 90,
                        'material_note' => 'PETG filament with good strength and chemical resistance.',
                    ]
                );
            }

            ProductImage::updateOrCreate(
                ['product_id' => $prusamentPetgClear->id, 'path' => 'storage/images/products/Prusament_PETG/101207_7c6e677e.512x512.avif'],
                ['alt_text' => 'Prusament PETG Clear', 'sort_order' => 1, 'is_primary' => true]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $prusamentPetgGold->id, 'path' => 'storage/images/products/Prusament_PETG/101087_cef540db.512x512.avif'],
                ['alt_text' => 'Prusament PETG Gold', 'sort_order' => 1, 'is_primary' => true]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $prusamentPetgWhite->id, 'path' => 'storage/images/products/Prusament_PETG/101111_c65d9dc1.512x512.avif'],
                ['alt_text' => 'Prusament PETG White', 'sort_order' => 1, 'is_primary' => true]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $prusamentPetgSalmon->id, 'path' => 'storage/images/products/Prusament_PETG/101117_1c2bf6c8.512x512.avif'],
                ['alt_text' => 'Prusament PETG Salmon', 'sort_order' => 1, 'is_primary' => true]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $prusamentPetgOrange->id, 'path' => 'storage/images/products/Prusament_PETG/101159_5e6c2ba7.512x512.avif'],
                ['alt_text' => 'Prusament PETG Orange', 'sort_order' => 1, 'is_primary' => true]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $prusamentPetgYellow->id, 'path' => 'storage/images/products/Prusament_PETG/101180_153a349a.512x512.avif'],
                ['alt_text' => 'Prusament PETG Yellow', 'sort_order' => 1, 'is_primary' => true]
            );

            $bambuFamily = ProductFamily::updateOrCreate(
                ['slug' => 'bambu-lab-a1-mini'],
                [
                    'category_id' => $printersCategory->id,
                    'name' => 'Bambu Lab A1 Mini',
                    'brand' => 'Bambulab',
                    'shared_short_description' => 'Compact desktop FDM printer family.',
                    'shared_description' => 'Compact 3D printer suitable for home and enthusiast use.',
                    'is_active' => true,
                ]
            );

            $bambuA1Mini = Product::updateOrCreate(
                ['slug' => 'bambulab-a1-mini'],
                [
                    'category_id' => $printersCategory->id,
                    'product_family_id' => $bambuFamily->id,
                    'color_id' => null,
                    'weight_id' => null,
                    'diameter_id' => null,
                    'variant_label' => 'Printer only',
                    'name' => 'A1 Mini',
                    'brand' => 'Bambulab',
                    'short_description' => 'Compact desktop FDM printer.',
                    'description' => 'Standalone A1 Mini printer.',
                    'price_gross' => 389.00,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.70,
                    'rating_count' => 24,
                    'stock_qty' => 4,
                    'is_active' => true,
                ]
            );

            $bambuA1MiniCombo = Product::updateOrCreate(
                ['slug' => 'bambulab-a1-mini-combo'],
                [
                    'category_id' => $printersCategory->id,
                    'product_family_id' => $bambuFamily->id,
                    'color_id' => null,
                    'weight_id' => null,
                    'diameter_id' => null,
                    'variant_label' => 'Printer + AMS',
                    'name' => 'A1 Mini Combo',
                    'brand' => 'Bambulab',
                    'short_description' => 'Compact desktop FDM printer with AMS.',
                    'description' => 'A1 Mini printer with AMS unit.',
                    'price_gross' => 489.00,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.80,
                    'rating_count' => 41,
                    'stock_qty' => 3,
                    'is_active' => true,
                ]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $bambuA1Mini->id, 'path' => 'storage/images/products/Printers/BambuLab_Printers/47068_ace7a3ed.512x512.avif'],
                ['alt_text' => 'Bambu Lab A1 Mini', 'sort_order' => 1, 'is_primary' => true]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $bambuA1MiniCombo->id, 'path' => 'storage/images/products/Printers/BambuLab_Printers/47101_6f35b580.512x512.avif'],
                ['alt_text' => 'Bambu Lab A1 Mini Combo', 'sort_order' => 1, 'is_primary' => true]
            );

            Product::updateOrCreate(
                ['slug' => 'elegoo-standard-resin-grey'],
                [
                    'category_id' => $standardResinCategory->id,
                    'product_family_id' => null,
                    'color_id' => $colorGrey->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => null,
                    'variant_label' => null,
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
                ['slug' => 'flush-cutters'],
                [
                    'category_id' => $toolsCategory->id,
                    'product_family_id' => null,
                    'color_id' => null,
                    'weight_id' => null,
                    'diameter_id' => null,
                    'variant_label' => null,
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

            $bambuPlaFamily = ProductFamily::updateOrCreate(
                ['slug' => 'bambulab-pla-matte'],
                [
                    'category_id' => $plaCategory->id,
                    'name' => 'BambuLab PLA Matte',
                    'brand' => 'Bambulab',
                    'shared_short_description' => 'Matte PLA filament for clean surface finish.',
                    'shared_description' => 'BambuLab PLA Matte is a PLA filament with a matte finish suitable for aesthetic prints and everyday printing.',
                    'is_active' => true,
                ]
            );

            $bambuPlaMatteWhite = Product::updateOrCreate(
                ['slug' => 'bambulab-pla-matte-white'],
                [
                    'category_id' => $plaCategory->id,
                    'product_family_id' => $bambuPlaFamily->id,
                    'color_id' => $colorWhite->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'variant_label' => null,
                    'name' => 'BambuLab Matte White, 1,75 mm / 1000 g',
                    'brand' => 'Bambulab',
                    'short_description' => 'Matte PLA filament with smooth surface finish.',
                    'description' => 'BambuLab Matte White PLA offers a clean matte look and easy printability for general-purpose printing.',
                    'price_gross' => 23.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.60,
                    'rating_count' => 18,
                    'stock_qty' => 10,
                    'is_active' => true,
                ]
            );

            $bambuPlaMatteBeige = Product::updateOrCreate(
                ['slug' => 'bambulab-pla-matte-beige'],
                [
                    'category_id' => $plaCategory->id,
                    'product_family_id' => $bambuPlaFamily->id,
                    'color_id' => $colorBeige->id,
                    'weight_id' => $weight1000->id,
                    'diameter_id' => $diameter175->id,
                    'variant_label' => null,
                    'name' => 'BambuLab Matte Beige, 1,75 mm / 1000 g',
                    'brand' => 'Bambulab',
                    'short_description' => 'Matte PLA filament with smooth surface finish.',
                    'description' => 'BambuLab Matte Beige PLA offers a warm matte look and easy printability for general-purpose printing.',
                    'price_gross' => 23.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.50,
                    'rating_count' => 14,
                    'stock_qty' => 8,
                    'is_active' => true,
                ]
            );

            foreach ([$bambuPlaMatteWhite, $bambuPlaMatteBeige] as $product) {
                FilamentDetail::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'filament_type_id' => $plaType->id,
                        'recommended_nozzle_temp_min' => 190,
                        'recommended_nozzle_temp_max' => 230,
                        'recommended_bed_temp_min' => 35,
                        'recommended_bed_temp_max' => 60,
                        'material_note' => 'Matte PLA for aesthetic prints and easy everyday use.',
                    ]
                );
            }

            ProductImage::updateOrCreate(
                ['product_id' => $bambuPlaMatteWhite->id, 'path' => 'storage/images/products/BambuLab_PLA/47878_59194eec.512x512.avif'],
                ['alt_text' => 'BambuLab Matte White main image', 'sort_order' => 1, 'is_primary' => true]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $bambuPlaMatteWhite->id, 'path' => 'storage/images/products/BambuLab_PLA/83050_930bc2b3.512x512.avif'],
                ['alt_text' => 'BambuLab Matte White additional image', 'sort_order' => 2, 'is_primary' => false]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $bambuPlaMatteBeige->id, 'path' => 'storage/images/products/BambuLab_PLA/47902_4fe00357.512x512.avif'],
                ['alt_text' => 'BambuLab Matte Beige main image', 'sort_order' => 1, 'is_primary' => true]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $bambuPlaMatteBeige->id, 'path' => 'storage/images/products/BambuLab_PLA/83104_80d66b9e.512x512.avif'],
                ['alt_text' => 'BambuLab Matte Beige additional image', 'sort_order' => 2, 'is_primary' => false]
            );

            $ezlokHeatInserts = Product::updateOrCreate(
                ['slug' => 'ezlok-heat-inserts'],
                [
                    'category_id' => $accessoriesCategory->id,
                    'product_family_id' => null,
                    'color_id' => null,
                    'weight_id' => null,
                    'diameter_id' => null,
                    'variant_label' => null,
                    'name' => 'EZLOK Heat Inserts 50 pcs',
                    'brand' => 'EZLOK',
                    'short_description' => 'Threaded heat inserts for 3D printed parts.',
                    'description' => 'Brass heat-set threaded inserts designed for durable threaded connections in 3D printed parts.',
                    'price_gross' => 9.99,
                    'tax_rate' => 23.00,
                    'rating_avg' => 4.50,
                    'rating_count' => 8,
                    'stock_qty' => 20,
                    'is_active' => true,
                ]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $ezlokHeatInserts->id, 'path' => 'storage/images/products/Accessories/ezlok_Heat_Inserts_1.png'],
                ['alt_text' => 'EZLOK Heat Inserts', 'sort_order' => 1, 'is_primary' => true]
            );
        });
    }
}