<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Voice;

class VoicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Voice::insert(
            [
                [
                    'uuid' => Str::uuid(),
                    'provider' => 'openai',
                    'model' => 'tts-1',
                    'status' => 1,
                    'sample' => 'https://cdn.openai.com/API/docs/audio/alloy.wav',
                    'token' => 'alloy',
                    'name' => 'Alloy',
                    'gender' => 1,
                    'accent' => null,
                    'age' => null,
                    'tone' => null,
                    'case' => 'general',
                    'created_at' => now(),
                ],
                [
                    'uuid' => Str::uuid(),
                    'provider' => 'openai',
                    'model' => 'tts-1',
                    'status' => 1,
                    'sample' => 'https://cdn.openai.com/API/docs/audio/echo.wav',
                    'token' => 'echo',
                    'name' => 'Echo',
                    'gender' => 1,
                    'accent' => null,
                    'age' => null,
                    'tone' => null,
                    'case' => 'general',
                    'created_at' => now(),
                ],
                [
                    'uuid' => Str::uuid(),
                    'provider' => 'openai',
                    'model' => 'tts-1',
                    'status' => 1,
                    'sample' => 'https://cdn.openai.com/API/docs/audio/fable.wav',
                    'token' => 'fable',
                    'name' => 'Fable',
                    'gender' => 1,
                    'accent' => null,
                    'age' => null,
                    'tone' => null,
                    'case' => 'general',
                    'created_at' => now(),
                ],
                [
                    'uuid' => Str::uuid(),
                    'provider' => 'openai',
                    'model' => 'tts-1',
                    'status' => 1,
                    'sample' => 'https://cdn.openai.com/API/docs/audio/onyx.wav',
                    'token' => 'onyx',
                    'name' => 'Onyx',
                    'gender' => 1,
                    'accent' => null,
                    'age' => null,
                    'tone' => null,
                    'case' => 'general',
                    'created_at' => now(),
                ],
                [
                    'uuid' => Str::uuid(),
                    'provider' => 'openai',
                    'model' => 'tts-1',
                    'status' => 1,
                    'sample' => 'https://cdn.openai.com/API/docs/audio/nova.wav',
                    'token' => 'nova',
                    'name' => 'Nova',
                    'gender' => 2,
                    'accent' => null,
                    'age' => null,
                    'tone' => null,
                    'case' => 'general',
                    'created_at' => now(),
                ],
                [
                    'uuid' => Str::uuid(),
                    'provider' => 'openai',
                    'model' => 'tts-1',
                    'status' => 1,
                    'sample' => 'https://cdn.openai.com/API/docs/audio/shimmer.wav',
                    'token' => 'shimmer',
                    'name' => 'Shimmer',
                    'gender' => 2,
                    'accent' => null,
                    'age' => null,
                    'tone' => null,
                    'case' => 'general',
                    'created_at' => now(),
                ],
            ]
        );
    }
}
