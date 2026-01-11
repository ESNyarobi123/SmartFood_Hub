<?php

namespace Database\Seeders;

use App\Models\SubscriptionPackage;
use Illuminate\Database\Seeder;

class SubscriptionPackageSeeder extends Seeder
{
    public function run(): void
    {
        SubscriptionPackage::create([
            'name' => 'Weekly Package',
            'description' => 'Chakula kwa wiki moja - meals 7 kwa wiki',
            'price' => 35000,
            'duration_type' => 'weekly',
            'meals_per_week' => 7,
            'delivery_days' => [0, 1, 2, 3, 4, 5, 6], // All days
            'is_active' => true,
        ]);

        SubscriptionPackage::create([
            'name' => 'Monthly Package',
            'description' => 'Chakula kwa mwezi - meals 7 kwa wiki',
            'price' => 120000,
            'duration_type' => 'monthly',
            'meals_per_week' => 7,
            'delivery_days' => [0, 1, 2, 3, 4, 5, 6], // All days
            'is_active' => true,
        ]);

        SubscriptionPackage::create([
            'name' => 'Weekday Package',
            'description' => 'Chakula siku za kazi tu - meals 5 kwa wiki',
            'price' => 90000,
            'duration_type' => 'monthly',
            'meals_per_week' => 5,
            'delivery_days' => [1, 2, 3, 4, 5], // Monday to Friday
            'is_active' => true,
        ]);
    }
}
