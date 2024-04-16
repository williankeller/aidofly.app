<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert(
            [
                [
                    'uuid' => Str::uuid(),
                    'title' => 'Writing Tools',
                    'created_at' => now(),
                ],
                [
                    'uuid' => Str::uuid(),
                    'title' => 'Ads',
                    'created_at' => now(),
                ],
                [
                    'uuid' => Str::uuid(),
                    'title' => 'Business and Strategy',
                    'created_at' => now(),
                ],
                [
                    'uuid' => Str::uuid(),
                    'title' => 'E-commerce',
                    'created_at' => now(),
                ],
                [
                    'uuid' => Str::uuid(),
                    'title' => 'Email',
                    'created_at' => now(),
                ],
                [
                    'uuid' => Str::uuid(),
                    'title' => 'Social Media',
                    'created_at' => now(),
                ],
                [
                    'uuid' => Str::uuid(),
                    'title' => 'Website',
                    'created_at' => now(),
                ],
            ]
        );
    }
}
