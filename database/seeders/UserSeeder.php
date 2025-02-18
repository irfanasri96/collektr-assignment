<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com',],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('test123'),
            ],
        );
    }
}
