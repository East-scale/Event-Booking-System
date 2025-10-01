<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a guest can view the paginated list of upcoming events.
     *
     * @return void
     */
    public function test_a_guest_can_view_the_paginated_list_of_upcoming_events()
    {
        // Create an organiser
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        
        // Create 10 upcoming events
        Event::factory()->count(10)->create([
            'organiser_id' => $organiser->id,
            'event_date' => now()->addDays(5),
        ]);
        
        // Create 2 past events (should not appear)
        Event::factory()->count(2)->create([
            'organiser_id' => $organiser->id,
            'event_date' => now()->subDays(5),
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHas('events');
        
        // Check pagination exists (8 events per page)
        $events = $response->viewData('events');
        $this->assertCount(8, $events);
        
        // Verify only upcoming events are shown
        foreach ($events as $event) {
            $this->assertTrue($event->event_date->isFuture());
        }
    }

    /**
     * Test that a guest can view a specific event details page.
     *
     * @return void
     */
    public function test_a_guest_can_view_a_specific_event_details_page()
    {
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Test Event',
            'event_date' => now()->addDays(7),
        ]);

        $response = $this->get(route('events.show', $event));

        $response->assertStatus(200);
        $response->assertViewIs('events.show');
        $response->assertViewHas('event');
        $response->assertSee($event->title);
        $response->assertSee($event->location);
    }

    /**
     * Test that a guest is redirected to login when accessing protected routes.
     *
     * @return void
     */
    public function test_a_guest_is_redirected_when_accessing_protected_routes()
    {
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $event = Event::factory()->create(['organiser_id' => $organiser->id]);

        // Test dashboard redirect
        $response = $this->get('/dashboard');
        $response->assertRedirect(route('login'));

        // Test event create redirect
        $response = $this->get(route('events.create'));
        $response->assertRedirect(route('login'));

        // Test event edit redirect
        $response = $this->get(route('events.edit', $event));
        $response->assertRedirect(route('login'));

        // Test my bookings redirect
        $response = $this->get(route('bookings.my-bookings'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Test that a guest viewing an event details page cannot see action buttons.
     *
     * @return void
     */
    public function test_a_guest_cannot_see_action_buttons_on_event_details_page()
    {
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'event_date' => now()->addDays(7),
        ]);

        $response = $this->get(route('events.show', $event));

        $response->assertStatus(200);
        
        // Guest should not see Book Now button
        $response->assertDontSee('Book Now');
        
        // Guest should not see Edit button
        $response->assertDontSee('Edit');
        
        // Guest should not see Delete button  
        $response->assertDontSee('Delete');
    }
}