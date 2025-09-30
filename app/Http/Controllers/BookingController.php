<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    // The function to store a new booking (when the user selects 'Book Now')
    public function store(Request $request, Event $event)
    {
        // 1. Manual Capacity validation (AN ASSIGNMENT REQUIREMENT)
        $currentBookings = Booking::where('event_id', $event->id)->count();
        if($currentBookings >= $event->capacity) {
            return redirect()->back()->with('error', 'Event is fully booked.');
        }
        // 2. Check if the event has already occurred
        if (!$event->isUpcoming()){
            return redirect()->back()->with('error', 'Event is full.');
        }

        // 3. Check if the user has already booked this event 
        $existingBooking = Booking::where('user_id', auth()->id())->where('event_id',$event->id)->first();

        // If the booking already exists, go back with an error message
        if ($existingBooking) {
            return redirect()->back()->with('error', 'You have already booked this event.');
        }
        // 4. Create the booking
        Booking::create([
            'user_id' => auth() ->id(), //access id property of authenticated user
            'event_id' => $event->id
        ]);
        return redirect()->back()->with('success', 'Event booked');
    }
        // Cancel a booking
        public function destroy(Booking $booking)
        {
            // Ensure a user can't cancel other user's bookings
                if ($booking->user_id !== auth()->id()) {
                    return redirect()->back()->with('error', 'You can only cancel your own bookings.');
                }
                $booking->delete();
                return redirect()->back()->with('success', 'You booking has been cancelled.');
        }


        public function myBookings()
        {
            // Ensure only authenticated users can access
            if (!auth()->check()) {
                return redirect()->route('login');
            }

            // Get all bookings for the current user with event details
            // Using raw SQL to match the assignment requirements
            $bookings = DB::select("
                SELECT 
                    b.id as booking_id,
                    b.created_at as booked_at,
                    e.id as event_id,
                    e.title,
                    e.description,
                    e.event_date,
                    e.capacity,
                    u.name as organiser_name
                FROM bookings b
                INNER JOIN events e ON b.event_id = e.id
                INNER JOIN users u ON e.organiser_id = u.id
                WHERE b.user_id = ?
                ORDER BY e.event_date ASC
            ", [auth()->id()]);

            return view('my-bookings', compact('bookings'));
        }

        public function cancelBooking($id)
        {
        try {
            // Verify the booking exists and belongs to the current user
            $booking = DB::selectOne("
                SELECT id, user_id, event_id 
                FROM bookings 
                WHERE id = ? AND user_id = ?
            ", [$id, auth()->id()]);

            if (!$booking) {
                return redirect()->route('bookings.my-bookings')
                            ->with('error', 'Booking not found or access denied.');
            }

            // Delete the booking
            DB::delete("DELETE FROM bookings WHERE id = ? AND user_id = ?", [$id, auth()->id()]);

            return redirect()->route('bookings.my-bookings')
                        ->with('success', 'Booking cancelled successfully.');

        } catch (\Exception $e) {
            return redirect()->route('bookings.my-bookings')
                        ->with('error', 'Unable to cancel booking. Please try again.');
        }
        }

}





