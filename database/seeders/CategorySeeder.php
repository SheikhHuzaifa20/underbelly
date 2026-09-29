<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            'Electronics' => [
                'Mobile Phones', 'Laptops', 'Cameras', 'Televisions', 'Headphones',
            ],
            'Fashion' => [
                'Men Clothing', 'Women Clothing', 'Shoes', 'Accessories', 'Jewelry',
            ],
            'Books' => [
                'Fiction', 'Non-Fiction', 'Comics', 'Educational', 'Biographies',
            ],
            'Home & Kitchen' => [
                'Furniture', 'Kitchen Appliances', 'Decor', 'Bedding', 'Bath',
            ],
            'Health & Beauty' => [
                'Personal Care', 'Makeup', 'Vitamins', 'Fitness Equipment', 'Hair Care',
            ],
            'Sports & Outdoors' => [
                'Equipment', 'Gear', 'Camping', 'Hiking', 'Fitness', 'Accessories',
            ],
            'Toys & Games' => [
                'Board Games', 'Video Games', 'Puzzles', 'Action Figures', 'Dolls',
            ],
            'Vehicles & Parts' => [
                'Groceries', 'Drinks', 'Gourmet Foods', 'Organic Foods', 'Snacks',
            ],
        ];

        foreach ($categories as $categoryName => $subcategories) {
            // Create category
            $category = Category::create([
                'name' => $categoryName,
                'description' => "$categoryName products",
                'status' => 1
            ]);

            // Create subcategories
            foreach ($subcategories as $subName) {
                SubCategory::create([
                    'category_id' => $category->id,
                    'name' => $subName,
                    'status' => true,
                ]);
            }
        }
    }
}
