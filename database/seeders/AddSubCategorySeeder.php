<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubCategory;

class AddSubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            'Engines', 'Pistons', 'Filters',
            'Brakes', 'Suspension', 'Exhausts',
            'Batteries', 'Tires', 'Lights',
            'Interior Accessories', 'Exterior Accessories', 'Audio Systems',
            'Performance Parts', 'Fluids & Lubricants', 'Tools & Equipment',
        ];

        foreach ($categories as $key => $subcategories) {
            SubCategory::create([
                'category_id' => 8,
                'name' => $subcategories,
                'status' => true,
            ]);
        }
    }
}
