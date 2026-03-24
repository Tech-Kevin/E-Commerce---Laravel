<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Electronics' => ['Mobile', 'Laptop', 'Camera', 'Accessories'],
            'Fashion' => ['Men Wear', 'Women Wear', 'Footwear', 'Watches'],
            'Home' => ['Furniture', 'Decor', 'Kitchen', 'Lighting'],
            'Sports' => ['Cricket', 'Football', 'Gym', 'Outdoor'],
        ];

        foreach ($categories as $categoryName => $subs) {
            $category = Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'status' => true,
            ]);

            foreach ($subs as $subName) {
                Subcategory::create([
                    'category_id' => $category->id,
                    'name' => $subName,
                    'slug' => Str::slug($subName),
                    'status' => true,
                ]);
            }
        }
    }
    
}
