<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestParentSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            [
                'email' => 'parent.test@tinyguide.local',
            ],
            [
                'name' => 'Test Parent',
                'password' => Hash::make('Test@123456'),
                'role' => 'user',
                'is_active' => true,
            ]
        );
    }
}