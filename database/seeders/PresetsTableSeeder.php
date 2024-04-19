<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Preset;

class PresetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Preset::insert([
            [
                'uuid' => Str::uuid(),
                'source' => 'system',
                'visibility' => 'public',
                'status' => true,
                'title' => 'Code writer',
                'description' => 'Generate high quality code in seconds given a specific programming language.',
                'template' => 'Write a clean code with the following requirements: { prompt | multiline | placeholder:Describe your code requirements here | info:The more details you provide, the better the code will be. }. Write the code in { technology | placeholder: Python, Typescript, CSS | info:You can add more than one language. } programming language.',
                'icon' => 'ti-code',
                'color' => '#dc3545',
                'category_id' => 3,
                'user_id' => null,
                'created_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'source' => 'system',
                'visibility' => 'public',
                'status' => true,
                'title' => 'Instagram Captions',
                'description' => "Craft engaging and relatable Instagram captions that highlight your product's uniqueness and connect with your target audience.",
                'template' => "Write 5 variations of Instagram captions for { product | placeholder: your new handmade jewelry collection | info: Describe your product, e.g., a collection of handcrafted jewelry }.
                Use friendly, human-like language that appeals to { target_audience | placeholder: fashion enthusiasts | info: Define your target audience, e.g., fashion enthusiasts }.
                Emphasize the unique qualities of { product | placeholder: these exquisite pieces | info: Highlight the distinct features of your jewelry line },
                use ample emojis, and don't sound too promotional.
                Additional instructions: - Write it in { language } language by using {tone} voice tone.",
                'icon' => 'ti-brand-instagram',
                'color' => '#E973A8',
                'category_id' => 2,
                'user_id' => null,
                'created_at' => now(),
            ]
        ]);
    }
}
