<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Technology',
                'description' => 'Tech conferences, workshops, and seminars',
                'color' => '#3B82F6',
                'is_active' => true
            ],
            [
                'name' => 'Business',
                'description' => 'Business networking and professional development',
                'color' => '#10B981',
                'is_active' => true
            ],
            [
                'name' => 'Education',
                'description' => 'Educational workshops and training sessions',
                'color' => '#F59E0B',
                'is_active' => true
            ],
            [
                'name' => 'Health & Wellness',
                'description' => 'Health, fitness, and wellness events',
                'color' => '#EF4444',
                'is_active' => true
            ],
            [
                'name' => 'Arts & Culture',
                'description' => 'Cultural events, exhibitions, and performances',
                'color' => '#8B5CF6',
                'is_active' => true
            ],
            [
                'name' => 'Sports & Recreation',
                'description' => 'Sports events and recreational activities',
                'color' => '#06B6D4',
                'is_active' => true
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}