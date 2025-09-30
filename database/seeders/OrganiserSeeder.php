<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OrganiserSeeder extends Seeder
{
    // Run the database seeds.   
    public function run(): void
    {
        User::create([
            'name' => 'John Kingston',
            'email' => 'john@gmail.com',
            'password' => Hash::make('password'),
            'user_type' => 'organiser',
        ]);

        User::create([
            'name' => 'Sarah Jane',
            'email' => 'sarah@gmail.com',
            'password' => Hash::make('password'),
            'user_type' => 'organiser',
        ]);
    }
}

