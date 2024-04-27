<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'uuid' => Str::uuid(),
                'email' => 'willianlkeller@outlook.com',
                'firstname' => 'Willian',
                'lastname' => 'Keller',
                'password' => Hash::make('willianlkeller@outlook.com'),
                'role' => 1,
                'status' => 1,
                'created_at' => now(),
            ],
        ]);
    }
}
