<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendeeActionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user can successfully register as an attendee.
     *
     * @return void
     */
    public function test_a_user_can_successfully_register_as_an_attendee()
    {
        $response = $this->post('/register', [
            'name' => 'Test Attendee',
            'email' => 'attendee@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'privacy_policy' => true,
        ]);

        $response->assertRedirect(route('home'));
        
        $this->assertDatabaseHas('users', [
            'name' => 'Test Attendee',
            'email' => 'attendee@test.com',
            'user_type' => 'attendee',
        ]);
        
        $this->assertAuthenticated();
    }

    /**
     * Test that a registered attendee can log in and log out.
     *
     * @return void
     */
    public function test_a_registered_attendee_can_log_in_and_log_out()
    {
        $attendee = User::factory()->create([
            'email' => 'attendee@test.com',
            'password' => bcrypt('password123'),
            'user_type' => 'attendee',
        ]);

        // Test login
        $response = $this->post('/login', [
            'email' => 'attendee@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($attendee);

        // Test logout
        $response = $this->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /**
     * Test that a logged-in attendee can book an available upcoming event.
     *
     * @return void
     */
    public function test_a_logged_in_attendee_can_book_an_available_upcoming_event()
    {
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'event_date' => now()->addDays(7),
            'capacity' => 10,
        ]);

        $response = $this->actingAs($attendee)->post(route('bookings.store', $event));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('bookings', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
        ]);
    }

    /**
     * Test that after booking, an attendee can see the event on their bookings page.
     *
     * @return void
     */
    public function test_after_booking_an_attendee_can_see_the_event_on_their_bookings_page()
    {
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'title' => 'My Booked Event',
            'event_date' => now()->addDays(7),
        ]);

        Booking::factory()->create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
        ]);

        $response = $this->actingAs($attendee)->get(route('bookings.my-bookings'));

        $response->assertStatus(200);
        $response->assertSee('My Booked Event');
        $response->assertSee($event->location);
    }

    /**
     * Test that an attendee cannot book the same event more than once.
     *
     * @return void
     */
    public function test_an_attendee_cannot_book_the_same_event_more_than_once()
    {
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'event_date' => now()->addDays(7),
            'capacity' => 10,
        ]);

        // Create first booking
        Booking::factory()->create([
            'user_id' => $attendee->id,
            'event_id' => $event->id,
        ]);

        // Attempt to book again
        $response = $this->actingAs($attendee)->post(route('bookings.store', $event));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'You have already booked this event.');
        
        // Verify only one booking exists
        $this->assertEquals(1, Booking::where('user_id', $attendee->id)
                                      ->where('event_id', $event->id)
                                      ->count());
    }

    /**
     * Test that an attendee cannot book a full event (manual capacity check).
     *
     * @return void
     */
    public function test_an_attendee_cannot_book_a_full_event()
    {
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'event_date' => now()->addDays(7),
            'capacity' => 2,
        ]);

        // Fill the event to capacity
        $otherAttendees = User::factory()->count(2)->create(['user_type' => 'attendee']);
        foreach ($otherAttendees as $otherAttendee) {
            Booking::factory()->create([
                'user_id' => $otherAttendee->id,
                'event_id' => $event->id,
            ]);
        }

        // Attempt to book when full
        $response = $this->actingAs($attendee)->post(route('bookings.store', $event));

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Event is fully booked.');
        
        $this->assertDatabaseMissing('bookings', [
            'user_id' => $attendee->id,
            'event_id' => $event->id,
        ]);
    }

    /**
     * Test that an attendee cannot see edit or delete buttons on any event page.
     *
     * @return void
     */
    public function test_an_attendee_cannot_see_edit_or_delete_buttons_on_any_event_page()
    {
        $attendee = User::factory()->create(['user_type' => 'attendee']);
        $organiser = User::factory()->create(['user_type' => 'organiser']);
        
        $event = Event::factory()->create([
            'organiser_id' => $organiser->id,
            'event_date' => now()->addDays(7),
        ]);

        $response = $this->actingAs($attendee)->get(route('events.show', $event));

        $response->assertStatus(200);
        $response->assertDontSee('Edit');
        $response->assertDontSee('Delete');
    }
}