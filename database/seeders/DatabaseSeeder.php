<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User if it doesn't exist
        User::firstOrCreate(
            ['email' => 'admin@smartfoodhub.com'],
            [
                'name' => 'Admin User',
                'is_admin' => true,
                'phone' => '0744963858',
                'address' => 'Dar es Salaam, Tanzania',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );

        // Create Regular User if it doesn't exist
        User::firstOrCreate(
            ['email' => 'user@smartfoodhub.com'],
            [
                'name' => 'Test User',
                'is_admin' => false,
                'phone' => '0755123456',
                'address' => 'Dar es Salaam, Tanzania',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );

        // Seed Categories, Food Items, and Packages
        $this->call([
            CategorySeeder::class,
            FoodItemSeeder::class,
            SubscriptionPackageSeeder::class,
        ]);
    }
}
