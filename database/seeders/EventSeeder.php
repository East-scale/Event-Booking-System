<?php

namespace Database\Seeders;

#use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon; # to capture the present time


class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organisers = User::where('user_type', 'organiser')->get();
        
        if ($organisers->isEmpty()) {
            // Run organiser seeder first if no organisers exist
            $this->call(OrganiserSeeder::class);
            $organisers = User::where('user_type', 'organiser')->get();
        }

        $events = [
            // Future events (upcoming)
            [
                'title' => 'Laravel Workshop',
                'description' => 'Learn the basics of Laravel framework development',
                'event_date' => Carbon::now()->addDays(5)->setTime(14, 0, 0),
                'location' => 'Tech Hub Brisbane',
                'capacity' => 25,
            ],
            [
                'title' => 'Web Development Bootcamp',
                'description' => 'Intensive 3-day bootcamp covering HTML, CSS, and JavaScript',
                'event_date' => Carbon::now()->addDays(10)->setTime(9, 0, 0),
                'location' => 'Brisbane Convention Centre',
                'capacity' => 50,
            ],
            [
                'title' => 'React Fundamentals',
                'description' => 'Introduction to React.js for beginners',
                'event_date' => Carbon::now()->addDays(15)->setTime(13, 30, 0),
                'location' => 'QUT Gardens Point',
                'capacity' => 30,
            ],
            [
                'title' => 'Database Design Seminar',
                'description' => 'Learn best practices for database design and optimization',
                'event_date' => Carbon::now()->addDays(20)->setTime(10, 0, 0),
                'location' => 'Griffith University',
                'capacity' => 40,
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Building mobile apps with Flutter',
                'event_date' => Carbon::now()->addDays(25)->setTime(15, 0, 0),
                'location' => 'South Bank Tech Center',
                'capacity' => 20,
            ],
            [
                'title' => 'AI and Machine Learning Intro',
                'description' => 'Getting started with artificial intelligence',
                'event_date' => Carbon::now()->addDays(30)->setTime(11, 0, 0),
                'location' => 'UQ St Lucia Campus',
                'capacity' => 35,
            ],
            [
                'title' => 'DevOps Essentials',
                'description' => 'Introduction to DevOps practices and tools',
                'event_date' => Carbon::now()->addDays(35)->setTime(14, 30, 0),
                'location' => 'Fortitude Valley Hub',
                'capacity' => 25,
            ],
            [
                'title' => 'Cybersecurity Workshop',
                'description' => 'Understanding web security fundamentals',
                'event_date' => Carbon::now()->addDays(40)->setTime(16, 0, 0),
                'location' => 'Brisbane Technology Park',
                'capacity' => 30,
            ],
            [
                'title' => 'Cloud Computing Basics',
                'description' => 'Introduction to AWS and cloud services',
                'event_date' => Carbon::now()->addDays(45)->setTime(9, 30, 0),
                'location' => 'Spring Hill Learning Center',
                'capacity' => 40,
            ],
            [
                'title' => 'API Development Workshop',
                'description' => 'Building RESTful APIs with Laravel',
                'event_date' => Carbon::now()->addDays(50)->setTime(13, 0, 0),
                'location' => 'West End Co-working Space',
                'capacity' => 20,
            ],
            
            // Past events (for testing)
            [
                'title' => 'JavaScript Fundamentals',
                'description' => 'Basic JavaScript programming concepts',
                'event_date' => Carbon::now()->subDays(10)->setTime(14, 0, 0),
                'location' => 'City Library',
                'capacity' => 30,
            ],
            [
                'title' => 'Python for Beginners',
                'description' => 'Introduction to Python programming',
                'event_date' => Carbon::now()->subDays(5)->setTime(10, 0, 0),
                'location' => 'Community Center',
                'capacity' => 25,
            ],
            [
                'title' => 'Git Version Control',
                'description' => 'Learn Git for collaborative development',
                'event_date' => Carbon::now()->subDays(15)->setTime(15, 30, 0),
                'location' => 'Tech Hub Brisbane',
                'capacity' => 20,
            ],
            [
                'title' => 'HTML & CSS Basics',
                'description' => 'Web design fundamentals',
                'event_date' => Carbon::now()->subDays(20)->setTime(11, 0, 0),
                'location' => 'Design Studio',
                'capacity' => 35,
            ],
            [
                'title' => 'Project Management for Developers',
                'description' => 'Agile methodologies and project planning',
                'event_date' => Carbon::now()->subDays(25)->setTime(13, 0, 0),
                'location' => 'Business Center Brisbane',
                'capacity' => 40,
            ],
        ];

        foreach ($events as $index => $eventData) {
            Event::create(array_merge($eventData, [
                'organiser_id' => $organisers[$index % $organisers->count()]->id,
            ]));
        }
    }
}