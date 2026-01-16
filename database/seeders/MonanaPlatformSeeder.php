<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonanaPlatformSeeder extends Seeder
{
    /**
     * Seed the Monana Platform with default data.
     */
    public function run(): void
    {
        // ================================================
        // CYBER CAFE - MEAL SLOTS
        // ================================================
        DB::table('cyber_meal_slots')->insert([
            [
                'name' => 'asubuhi',
                'display_name' => 'Asubuhi',
                'order_start_time' => '01:00:00',
                'order_end_time' => '06:30:00',
                'delivery_time_label' => 'Kesho Asubuhi (6:00 - 9:00 AM)',
                'description' => 'Agiza chakula cha asubuhi. Order inafungwa 6:30 AM.',
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'mchana',
                'display_name' => 'Mchana',
                'order_start_time' => '01:00:00',
                'order_end_time' => '09:30:00',
                'delivery_time_label' => 'Leo Mchana (12:00 - 2:00 PM)',
                'description' => 'Agiza chakula cha mchana. Order inafungwa 9:30 AM.',
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'usiku',
                'display_name' => 'Usiku',
                'order_start_time' => '14:00:00',
                'order_end_time' => '18:30:00',
                'delivery_time_label' => 'Leo Usiku (6:00 - 9:00 PM)',
                'description' => 'Agiza chakula cha usiku. Order inafungwa 6:30 PM.',
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ================================================
        // CYBER CAFE - MENU ITEMS
        // ================================================
        DB::table('cyber_menu_items')->insert([
            [
                'name' => 'Chapati',
                'description' => 'Chapati laini na tamu',
                'price' => 500.00,
                'meal_slot_id' => null,
                'available_all_slots' => true,
                'is_available' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chai',
                'description' => 'Chai ya maziwa ya moto',
                'price' => 1000.00,
                'meal_slot_id' => 1, // Asubuhi
                'available_all_slots' => false,
                'is_available' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mayai (Boiled)',
                'description' => 'Mayai ya kuchemsha',
                'price' => 500.00,
                'meal_slot_id' => null,
                'available_all_slots' => true,
                'is_available' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mayai (Fried)',
                'description' => 'Mayai ya kukaanga',
                'price' => 800.00,
                'meal_slot_id' => null,
                'available_all_slots' => true,
                'is_available' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kachori',
                'description' => 'Kachori za kunde',
                'price' => 300.00,
                'meal_slot_id' => 1, // Asubuhi
                'available_all_slots' => false,
                'is_available' => true,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mandazi',
                'description' => 'Mandazi matamu',
                'price' => 200.00,
                'meal_slot_id' => 1, // Asubuhi
                'available_all_slots' => false,
                'is_available' => true,
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pilau',
                'description' => 'Pilau ya nyama',
                'price' => 5000.00,
                'meal_slot_id' => 2, // Mchana
                'available_all_slots' => false,
                'is_available' => true,
                'sort_order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Wali + Mchuzi',
                'description' => 'Wali na mchuzi wa nyama/kuku',
                'price' => 4000.00,
                'meal_slot_id' => 2, // Mchana
                'available_all_slots' => false,
                'is_available' => true,
                'sort_order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ugali + Samaki',
                'description' => 'Ugali na samaki wa kukaanga',
                'price' => 6000.00,
                'meal_slot_id' => 3, // Usiku
                'available_all_slots' => false,
                'is_available' => true,
                'sort_order' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chips Mayai',
                'description' => 'Chips na mayai',
                'price' => 3500.00,
                'meal_slot_id' => null,
                'available_all_slots' => true,
                'is_available' => true,
                'sort_order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ================================================
        // MONANA FOOD - PRODUCTS
        // ================================================
        DB::table('food_products')->insert([
            [
                'name' => 'Mchele',
                'description' => 'Mchele wa kupika wali',
                'price' => 3000.00,
                'unit' => 'kg',
                'stock_quantity' => 100,
                'is_available' => true,
                'can_be_customized' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tambi',
                'description' => 'Tambi za kupika',
                'price' => 2500.00,
                'unit' => 'kg',
                'stock_quantity' => 100,
                'is_available' => true,
                'can_be_customized' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mayai',
                'description' => 'Mayai ya kuku',
                'price' => 500.00,
                'unit' => 'pieces',
                'stock_quantity' => 200,
                'is_available' => true,
                'can_be_customized' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maharage',
                'description' => 'Maharage ya kupika',
                'price' => 2000.00,
                'unit' => 'kg',
                'stock_quantity' => 80,
                'is_available' => true,
                'can_be_customized' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Unga wa Ugali',
                'description' => 'Unga wa kutengeneza ugali',
                'price' => 1500.00,
                'unit' => 'kg',
                'stock_quantity' => 100,
                'is_available' => true,
                'can_be_customized' => true,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mafuta ya Kupikia',
                'description' => 'Mafuta ya alizeti',
                'price' => 4000.00,
                'unit' => 'liters',
                'stock_quantity' => 50,
                'is_available' => true,
                'can_be_customized' => true,
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sukari',
                'description' => 'Sukari nyeupe',
                'price' => 3500.00,
                'unit' => 'kg',
                'stock_quantity' => 100,
                'is_available' => true,
                'can_be_customized' => true,
                'sort_order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Chumvi',
                'description' => 'Chumvi ya kupikia',
                'price' => 500.00,
                'unit' => 'kg',
                'stock_quantity' => 100,
                'is_available' => true,
                'can_be_customized' => true,
                'sort_order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Nyanya',
                'description' => 'Nyanya fresh',
                'price' => 2000.00,
                'unit' => 'kg',
                'stock_quantity' => 50,
                'is_available' => true,
                'can_be_customized' => true,
                'sort_order' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vitunguu',
                'description' => 'Vitunguu maji',
                'price' => 3000.00,
                'unit' => 'kg',
                'stock_quantity' => 50,
                'is_available' => true,
                'can_be_customized' => true,
                'sort_order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sausage',
                'description' => 'Sausage za nyama',
                'price' => 1000.00,
                'unit' => 'pieces',
                'stock_quantity' => 100,
                'is_available' => true,
                'can_be_customized' => true,
                'sort_order' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maziwa',
                'description' => 'Maziwa fresh',
                'price' => 2500.00,
                'unit' => 'liters',
                'stock_quantity' => 50,
                'is_available' => true,
                'can_be_customized' => true,
                'sort_order' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ================================================
        // MONANA FOOD - PACKAGES
        // ================================================
        DB::table('food_packages')->insert([
            [
                'name' => 'Weekly Package',
                'description' => 'Kifurushi cha wiki - Bidhaa za msingi za jikoni kwa siku 7',
                'base_price' => 35000.00,
                'duration_days' => 7,
                'duration_type' => 'weekly',
                'deliveries_per_week' => 5,
                'delivery_days' => json_encode([1, 2, 3, 4, 5]), // Mon-Fri
                'customization_cutoff_time' => '18:00:00',
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Monthly Package',
                'description' => 'Kifurushi cha mwezi - Bidhaa za msingi za jikoni kwa siku 30',
                'base_price' => 120000.00,
                'duration_days' => 30,
                'duration_type' => 'monthly',
                'deliveries_per_week' => 5,
                'delivery_days' => json_encode([1, 2, 3, 4, 5]), // Mon-Fri
                'customization_cutoff_time' => '18:00:00',
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Premium Package',
                'description' => 'Kifurushi bora - Bidhaa nyingi zaidi na ubora wa juu',
                'base_price' => 180000.00,
                'duration_days' => 30,
                'duration_type' => 'monthly',
                'deliveries_per_week' => 6,
                'delivery_days' => json_encode([1, 2, 3, 4, 5, 6]), // Mon-Sat
                'customization_cutoff_time' => '20:00:00',
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // ================================================
        // MONANA FOOD - PACKAGE ITEMS
        // ================================================
        // Weekly Package Items
        DB::table('food_package_items')->insert([
            ['package_id' => 1, 'product_id' => 1, 'default_quantity' => 2, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Mchele 2kg
            ['package_id' => 1, 'product_id' => 2, 'default_quantity' => 1, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Tambi 1kg
            ['package_id' => 1, 'product_id' => 3, 'default_quantity' => 10, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Mayai 10
            ['package_id' => 1, 'product_id' => 4, 'default_quantity' => 1, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Maharage 1kg
            ['package_id' => 1, 'product_id' => 5, 'default_quantity' => 2, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Unga 2kg
        ]);

        // Monthly Package Items
        DB::table('food_package_items')->insert([
            ['package_id' => 2, 'product_id' => 1, 'default_quantity' => 8, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Mchele 8kg
            ['package_id' => 2, 'product_id' => 2, 'default_quantity' => 4, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Tambi 4kg
            ['package_id' => 2, 'product_id' => 3, 'default_quantity' => 30, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Mayai 30
            ['package_id' => 2, 'product_id' => 4, 'default_quantity' => 4, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Maharage 4kg
            ['package_id' => 2, 'product_id' => 5, 'default_quantity' => 8, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Unga 8kg
            ['package_id' => 2, 'product_id' => 6, 'default_quantity' => 2, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Mafuta 2L
            ['package_id' => 2, 'product_id' => 7, 'default_quantity' => 2, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Sukari 2kg
        ]);

        // Premium Package Items
        DB::table('food_package_items')->insert([
            ['package_id' => 3, 'product_id' => 1, 'default_quantity' => 10, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Mchele 10kg
            ['package_id' => 3, 'product_id' => 2, 'default_quantity' => 5, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Tambi 5kg
            ['package_id' => 3, 'product_id' => 3, 'default_quantity' => 60, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Mayai 60
            ['package_id' => 3, 'product_id' => 4, 'default_quantity' => 5, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Maharage 5kg
            ['package_id' => 3, 'product_id' => 5, 'default_quantity' => 10, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Unga 10kg
            ['package_id' => 3, 'product_id' => 6, 'default_quantity' => 5, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Mafuta 5L
            ['package_id' => 3, 'product_id' => 7, 'default_quantity' => 5, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Sukari 5kg
            ['package_id' => 3, 'product_id' => 9, 'default_quantity' => 3, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Nyanya 3kg
            ['package_id' => 3, 'product_id' => 10, 'default_quantity' => 2, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Vitunguu 2kg
            ['package_id' => 3, 'product_id' => 12, 'default_quantity' => 5, 'is_required' => false, 'created_at' => now(), 'updated_at' => now()], // Maziwa 5L
        ]);

        // ================================================
        // MONANA FOOD - PACKAGE RULES (Sample swap rules)
        // ================================================
        DB::table('food_package_rules')->insert([
            // Swap Mayai with Sausage - +500 TZS per piece
            ['package_id' => 1, 'from_product_id' => 3, 'to_product_id' => 11, 'adjustment_type' => 'fixed', 'adjustment_value' => 500, 'is_allowed' => true, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'from_product_id' => 3, 'to_product_id' => 11, 'adjustment_type' => 'fixed', 'adjustment_value' => 500, 'is_allowed' => true, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 3, 'from_product_id' => 3, 'to_product_id' => 11, 'adjustment_type' => 'fixed', 'adjustment_value' => 500, 'is_allowed' => true, 'created_at' => now(), 'updated_at' => now()],

            // Swap Mchele with Tambi - no price change
            ['package_id' => 1, 'from_product_id' => 1, 'to_product_id' => 2, 'adjustment_type' => 'fixed', 'adjustment_value' => 0, 'is_allowed' => true, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'from_product_id' => 1, 'to_product_id' => 2, 'adjustment_type' => 'fixed', 'adjustment_value' => 0, 'is_allowed' => true, 'created_at' => now(), 'updated_at' => now()],

            // Swap Maharage with Mchele - -500 per kg
            ['package_id' => 1, 'from_product_id' => 4, 'to_product_id' => 1, 'adjustment_type' => 'fixed', 'adjustment_value' => -500, 'is_allowed' => true, 'created_at' => now(), 'updated_at' => now()],
            ['package_id' => 2, 'from_product_id' => 4, 'to_product_id' => 1, 'adjustment_type' => 'fixed', 'adjustment_value' => -500, 'is_allowed' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ================================================
        // UPDATE SETTINGS
        // ================================================
        DB::table('settings')->updateOrInsert(
            ['key' => 'platform_name'],
            ['value' => 'Monana Platform', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('settings')->updateOrInsert(
            ['key' => 'cyber_cafe_name'],
            ['value' => 'Monana Cyber Cafe', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('settings')->updateOrInsert(
            ['key' => 'food_service_name'],
            ['value' => 'Monana Food', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('settings')->updateOrInsert(
            ['key' => 'cyber_whatsapp_number'],
            ['value' => '+255700000001', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('settings')->updateOrInsert(
            ['key' => 'food_whatsapp_number'],
            ['value' => '+255700000002', 'created_at' => now(), 'updated_at' => now()]
        );
    }
}
