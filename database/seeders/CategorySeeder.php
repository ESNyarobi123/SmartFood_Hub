<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Food Categories
        Category::create([
            'name' => 'Vyakula vya Mchana',
            'description' => 'Vyakula vya mchana vilivyopikwa kwa ubora',
            'type' => 'food',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Vyakula vya Jioni',
            'description' => 'Vyakula vya jioni vilivyopikwa kwa ubora',
            'type' => 'food',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Vyakula vya Asubuhi',
            'description' => 'Vyakula vya asubuhi vilivyopikwa kwa ubora',
            'type' => 'food',
            'is_active' => true,
        ]);

        // Kitchen Product Categories
        Category::create([
            'name' => 'Vyombo vya Jikoni',
            'description' => 'Vyombo mbalimbali vya jikoni',
            'type' => 'kitchen',
            'is_active' => true,
        ]);

        Category::create([
            'name' => 'Vifaa vya Kupikia',
            'description' => 'Vifaa mbalimbali vya kupikia',
            'type' => 'kitchen',
            'is_active' => true,
        ]);
    }
}
