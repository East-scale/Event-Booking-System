<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    //Show method
    public function show(Event $event)
    {
        $userBooking = null;
        
        // Check if current user has already booked this event
        if (auth()->check()) {
            $userBooking = Booking::where('organiser_id', auth()->id())
                                ->where('event_id', $event->id)
                                ->first();
        }
        
        return view('events.show', compact('event', 'userBooking'));
    }

    // below is the methods for each crud functionality
    public function create()
    {
        //Check if the user is organiser. Only organisers can create events
        if (auth()->user()->user_type !== 'organiser'){
            return redirect()->route('home')->with('error', 'Only organisers may create events');
        }

        return view('events.create');
    }
    
    // Store a new event
    public function store(Request $request)
    {
        //Check if user is an organiser
        if (auth()->user()->user_type !== 'organiser'){
            return redirect()->route('home')->with('error', 'Only organisers may create events');
        }

        // Validation rules, using validate
        // Laravel throws a ValidationException error and stops the code if rules are not met
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'event_date' => 'required|date|after:today',
            'event_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:1000'
        ]);

        // Create the event, pass validated values into the event array
        $event = Event::create([
            'organiser_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'],
            'location' => $validated['location'],
            'capacity' => $validated['capacity']
        ]);

        return redirect()->route('events.show', $event)->with('success', 'Event created successfully!');
    }

    // Show edit form (event owner only)
    public function edit(Event $event)
    {
        // Only the event owner can access the edit form
        if ($event->organiser_id !== auth()->id()) {
            return redirect()->route('events.show', $event)->with('error', 'You can only edit your own events.');
        }

        return view('events.edit', compact('event'));
    }

    // Update event
    public function update(Request $request, Event $event)
    {
        // Only the event owner can update
        if ($event->organiser_id !== auth()->id()) {
            return redirect()->route('events.show', $event)->with('error', 'only the owner can update event details.');
        }

        // Validation is required here
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'event_date' => 'required|date|after:today',
            'event_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:1000'
        ]);

        // Update the event
        $event->update($validated);

        return redirect()->route('events.show', $event)->with('success', 'Event updated successfully!');

    }

    // Delete Event
    public function destroy(Event $event)
    {
        // Again check if it is the owner
        if ($event->organiser_id !== auth()->id()) {
            return redirect()->route('events.show', $event)->with('error', 'You can only delete your own events.');
        }

        // Check if event has any bookings, if so, cannot delete
        $bookingCount = Booking::where('event_id', $event->id)->count();
        if ($bookingCount > 0) {
            return redirect()->route('events.show', $event)->with('error', 'Cannot delete event with existing bookings.');
        }

        
        $eventTitle = $event->title; // Retrieve and store event title before deletion for the deletion message
       
        $event->delete(); // delete event

        return redirect()->route('home')->with('success', "Event '$eventTitle' deleted successfully!");
    }

    public function dashboard()
    {
    // Ensure only authenticated users can access the dashboard
    if (!auth()->check()) {
        return redirect()->route('login');
    }


    // Raw SQL query using DB::select() with parameter binding, LEFT JOIN is to ensure that events with zero bookings 
    // are also included. 
    $events = DB::select("
        SELECT 
            e.id,
            e.title,
            e.event_date,
            e.capacity,
            COALESCE(COUNT(b.id), 0) as current_bookings,
            (e.capacity - COALESCE(COUNT(b.id), 0)) as remaining_spots
        FROM events e
        LEFT JOIN bookings b ON e.id = b.event_id
        WHERE e.organiser_id = ?
        GROUP BY e.id, e.title, e.event_date, e.capacity
        ORDER BY e.event_date ASC
    ", [auth()->id()]);

    return view('dashboard', compact('events'));
    }

}



