<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organiser_id' => User::factory()->create(['user_type' => 'organiser']),
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'event_date' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'event_time' => fake()->time('H:i'),
            'location' => fake()->address(),
            'capacity' => fake()->numberBetween(10, 100),
        ];
    }

    /**
     * Indicate that the event is in the past.
     */
    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_date' => fake()->dateTimeBetween('-3 months', '-1 day'),
        ]);
    }

    /**
     * Indicate that the event is upcoming.
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_date' => fake()->dateTimeBetween('+1 day', '+3 months'),
        ]);
    }

    /**
     * Indicate that the event is full.
     */
    public function full(): static
    {
        return $this->state(fn (array $attributes) => [
            'capacity' => 0,
        ]);
    }
}