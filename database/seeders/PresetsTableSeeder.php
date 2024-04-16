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
                'category_id' => 1,
                'type' => 'completion',
                'visibility' => 'public',
                'status' => true,
                'title' => 'Content Improver',
                'description' => 'Take a piece of content and rewrite it to make it more interesting, creative, and engaging.',
                'template' => 'Rewrite following content to make it more interesting, creative, and engaging: { content | multiline | placeholder:Your content here | info:Type content that you would like to improve }. Use following type of content: { type_of_content | placeholder: Examples: Blog post, Ad Copy, Email | info:Enter type of content you would like to create. }
                Additional instructions: - Write it in { language } language by using {tone} voice tone.',
                'icon' => 'sparkles',
                'color' => '#5D38A7',
                'created_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'category_id' => 1,
                'type' => 'completion',
                'visibility' => 'public',
                'status' => true,
                'title' => 'Blog Post Generator',
                'description' => 'Generate a blog post on a given topic.',
                'template' => 'Generate a blog post on { topic | placeholder:Enter topic | info:Enter the topic you would like to write about. } Additional instructions: - Write it in { language } language by using {tone} voice tone.',
                'icon' => 'document-text',
                'color' => '#F59E0B',
                'created_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'category_id' => 6,
                'type' => 'completion',
                'visibility' => 'public',
                'status' => true,
                'title' => 'Instagram Captions',
                'description' => "Craft engaging and relatable Instagram captions that highlight your product's uniqueness and connect with your target audience.",
                'template' => "Write 5 variations of Instagram captions for { product | placeholder: your new handmade jewelry collection | info: Describe your product, e.g., a collection of handcrafted jewelry }.
                Use friendly, human-like language that appeals to { target_audience | placeholder: fashion enthusiasts | info: Define your target audience, e.g., fashion enthusiasts }.
                Emphasize the unique qualities of { product | placeholder: these exquisite pieces | info: Highlight the distinct features of your jewelry line },
                use ample emojis, and don't sound too promotional.
                Additional instructions: - Write it in { language } language by using {tone} voice tone.",
                'icon' => 'brand-instagram',
                'color' => '#E973A8',
                'created_at' => now(),
            ]
        ]);
    }
}
