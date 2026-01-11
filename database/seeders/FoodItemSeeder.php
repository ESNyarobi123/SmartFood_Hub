<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\FoodItem;
use Illuminate\Database\Seeder;

class FoodItemSeeder extends Seeder
{
    public function run(): void
    {
        $foodCategories = Category::where('type', 'food')->get();

        if ($foodCategories->isEmpty()) {
            return;
        }

        $lunchCategory = $foodCategories->first();
        $dinnerCategory = $foodCategories->skip(1)->first() ?? $foodCategories->first();
        $breakfastCategory = $foodCategories->skip(2)->first() ?? $foodCategories->first();

        // Breakfast Items
        FoodItem::create([
            'category_id' => $breakfastCategory->id,
            'name' => 'Chapati na Mayai',
            'description' => 'Chapati tamu na mayai yaliyokaangwa',
            'price' => 3000,
            'is_available' => true,
        ]);

        FoodItem::create([
            'category_id' => $breakfastCategory->id,
            'name' => 'Mandazi na Chai',
            'description' => 'Mandazi mawili na chai bora',
            'price' => 2000,
            'is_available' => true,
        ]);

        // Lunch Items
        FoodItem::create([
            'category_id' => $lunchCategory->id,
            'name' => 'Wali wa Nazi na Mboga',
            'description' => 'Wali wa nazi na mboga mchanganyiko',
            'price' => 5000,
            'is_available' => true,
        ]);

        FoodItem::create([
            'category_id' => $lunchCategory->id,
            'name' => 'Pilau',
            'description' => 'Pilau bora na kachumbari',
            'price' => 6000,
            'is_available' => true,
        ]);

        FoodItem::create([
            'category_id' => $lunchCategory->id,
            'name' => 'Ugali na Samaki',
            'description' => 'Ugali na samaki wa kupaka',
            'price' => 7000,
            'is_available' => true,
        ]);

        // Dinner Items
        FoodItem::create([
            'category_id' => $dinnerCategory->id,
            'name' => 'Nyama Choma',
            'description' => 'Nyama choma bora na kachumbari',
            'price' => 10000,
            'is_available' => true,
        ]);

        FoodItem::create([
            'category_id' => $dinnerCategory->id,
            'name' => 'Chicken Curry',
            'description' => 'Chicken curry na wali',
            'price' => 8000,
            'is_available' => true,
        ]);
    }
}
