<?php

namespace Database\Seeders;

# use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; //For password hashing


class AttendeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //random generated names
        $attendees = [
            ['name' => 'Alice Brown', 'email' => 'alice@example.com'],
            ['name' => 'Bob Wilson', 'email' => 'bob@example.com'],
            ['name' => 'Charlie Davis', 'email' => 'charlie@example.com'],
            ['name' => 'Diana Miller', 'email' => 'diana@example.com'],
            ['name' => 'Emma Taylor', 'email' => 'emma@example.com'],
            ['name' => 'Frank Jones', 'email' => 'frank@example.com'],
            ['name' => 'Grace Lee', 'email' => 'grace@example.com'],
            ['name' => 'Henry Clark', 'email' => 'henry@example.com'],
            ['name' => 'Ivy Anderson', 'email' => 'ivy@example.com'],
            ['name' => 'Jack Thompson', 'email' => 'jack@example.com'],
            ['name' => 'Kate Roberts', 'email' => 'kate@example.com'],
            ['name' => 'Liam Garcia', 'email' => 'liam@example.com'],
        ];

        // Give password to each attendee and mark as attendee user type
        foreach ($attendees as $attendee) {
            User::create(array_merge($attendee, [
                'password' => Hash::make('password'),
                'user_type' => 'attendee',
            ]));
        }
    }
}