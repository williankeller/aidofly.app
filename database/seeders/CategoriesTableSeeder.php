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
                    'id' => 1,
                    'uuid' => Str::uuid(),
                    'title' => 'Writing Tools',
                    'created_at' => now(),
                ],
                [
                    'id' => 2,
                    'uuid' => Str::uuid(),
                    'title' => 'Social Media',
                    'created_at' => now(),
                ],
                [
                    'id' => 3,
                    'uuid' => Str::uuid(),
                    'title' => 'Coding',
                    'created_at' => now(),
                ],
            ]
        );
    }
}
