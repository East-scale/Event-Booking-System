<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganiserActionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an organiser can log in and view their specific dashboard.
     *
     * @return void
     */
    public function test_an_organiser_can_log_in_and_view_their_specific_dashboard()
    {
        $organiser = User::factory()->create([
            'email' => 'organiser@test.com',
            'password' => bcrypt('password123'),
            'user_type' => 'organiser',
        ]);

        // Create some events for this organiser
        Event::factory()->count(3)->create(['organiser_id' => $organiser->id]);

        // Login
        $response = $this->post('/login', [
            'email' => 'organiser@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));

        // View dashboard
        $response = $this->actingAs($organiser)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('dashboard');
        $response->assertViewHas('events');
        
        // Verify the events are from raw SQL query
        $events = $response->viewData('events');
        $this->assertCount(3, $events);
    }

    /**
     * Test that an organiser can successfully create an event with valid data.
     *
     * @return void
     */
    public function test_an_organiser_can_successfully_create_an_event_with_valid_data()
    {
        $organiser = User::factory()->create(['user_type' => 'organiser']);

        $eventData = [
            'title' => 'Test Event',
            'description' => 'This is a test event description',
            'event_date' => now()->addDays(10)->format('Y-m-d'),
            'event_time' => '14:30',
            'location' => 'Test Location',
            'capacity' => 50,
        ];

        $response = $this->actingAs($organiser)->post(route('events.store'), $eventData);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('events', [
            'title' => 'Test Event',
            'location' => 'Test Location',
            'capacity' => 50,
            'organiser_id' => $organiser->id,
        ]);
    }

    /**
     * Test that an organiser receives validation errors for invalid event data.
     *
     * @return void
     */
    public function test_an_organiser_receives_validation_errors_for_invalid_event_data()
    {
        $organiser = User::factory()->create(['user_type' => 'organiser']);

        // Test with missing required fields
        $response = $this->actingAs($organiser)->post(route('events.store'), [
            'title' => '',
            'event_date' => '',
            'location' => '',
            'capacity' => '',
        ]);

        $response->assertSessionHasErrors(['title', 'event_date', 'location', 'capacity']);

        // Test with past date
        $response = $this->actingAs($organiser)->post(route('events.store'), [
            'title' => 'Test Event',
            'event_date' => now()->subDays(1)->format('Y-m-d'),
            'event_time' => '14:30',
            'location' => 'Test Location',
            'capacity' => 50,
        ]);

        $response->assertSessionHasErrors(['event_date']);

        // Test with capacity out of bounds
        $response = $this->actingAs($organiser)->post(route('events.store'), [
            'title' => 'Test Event',
            'event_date' => now()->addDays(10)->format('Y-m-d'),
            'event_time' => '14:30',
            'location' => 'Test Location',
            'capacity' => 1500, // Exceeds max of 1000
        ]);

        $response->assertSessionHasErrors(['capacity']);
    }

    /**
     * Test that an organiser can successfully update an event they own.
     *
     * @return void
     */
    public function test_an_organiser_can_successfully_update_an_event_they_own()
    {
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Original Title',
            'capacity' => 50,
        ]);

        $updatedData = [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'event_date' => now()->addDays(15)->format('Y-m-d'),
            'event_time' => '16:00',
            'location' => 'Updated Location',
            'capacity' => 75,
        ];

        $response = $this->actingAs($organiser)->patch(route('events.update', $event), $updatedData);

        $response->assertRedirect(route('events.show', $event));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Updated Title',
            'capacity' => 75,
            'location' => 'Updated Location',
        ]);
    }

    /**
     * Test that an organiser cannot update an event created by another organiser.
     *
     * @return void
     */
    public function test_an_organiser_cannot_update_an_event_created_by_another_organiser()
    {
        $organiser1 = User::factory()->create(['user_type' => 'organiser']);
        $organiser2 = User::factory()->create(['user_type' => 'organiser']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser1->id,
            'title' => 'Original Title',
        ]);

        $updatedData = [
            'title' => 'Hacked Title',
            'description' => 'Hacked description',
            'event_date' => now()->addDays(15)->format('Y-m-d'),
            'event_time' => '16:00',
            'location' => 'Hacked Location',
            'capacity' => 75,
        ];

        $response = $this->actingAs($organiser2)->patch(route('events.update', $event), $updatedData);

        $response->assertRedirect(route('events.show', $event));
        $response->assertSessionHas('error');
        
        // Verify the event was NOT updated
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Original Title',
        ]);
        
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
            'title' => 'Hacked Title',
        ]);
    }

    /**
     * Test that an organiser can delete an event they own that has no bookings.
     *
     * @return void
     */
    public function test_an_organiser_can_delete_an_event_they_own_that_has_no_bookings()
    {
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Event To Delete',
        ]);

        $response = $this->actingAs($organiser)->delete(route('events.destroy', $event));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('events', [
            'id' => $event->id,
        ]);
    }

    /**
     * Test that an organiser cannot delete an event that has active bookings.
     *
     * @return void
     */
    public function test_an_organiser_cannot_delete_an_event_that_has_active_bookings()
    {
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'Event With Bookings',
        ]);

        // Create a booking for this event
        Booking::factory()->create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
        ]);

        $response = $this->actingAs($organiser)->delete(route('events.destroy', $event));

        $response->assertRedirect(route('events.show', $event));
        $response->assertSessionHas('error', 'Cannot delete event with existing bookings.');
        
        // Verify the event still exists
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Event With Bookings',
        ]);
    }
}