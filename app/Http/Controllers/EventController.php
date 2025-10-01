<?php

namespace App\Http\Controllers;

//Import the models for interacting with the Event and Bookings tables
use App\Models\Event;
use App\Models\Category; //Category feature: Import model for filtering 
use App\Models\Booking;

use Illuminate\Http\Request; //Request class for handling HTTP requests
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; //required for raw database queries

//EventController handles the CRUD operations for events and dashboard functionality
class EventController extends Controller
{    
    
    /**
    * Display a listing of all events for public browsing
    */
    public function index()
    {
    // Get all events for attendees to browse and book
    $events = Event::with(['organiser', 'categories'])
        ->orderBy('event_date', 'asc')
        ->simplePaginate(8);
    
    // Add the variables that home.blade.php expects
    $categories = Category::where('is_active', true)->orderBy('name')->get();
    $selectedCategory = null;
        
    return view('home', compact('events', 'categories', 'selectedCategory'));
    }

    //Show the details of a specific event
    public function show(Event $event)
    {
        $userBooking = null; //initialise userbooking as null if a user has not booked anything
        
        // Check if current user has already booked this event
        if (auth()->check()) {
            $userBooking = Booking::where('user_id', auth()->id())
                                ->where('event_id', $event->id)
                                ->first();
        }

        // Check if the event is Full
        $currentBookings = Booking::where('event_id', $event->id)->count();
        $isEventFull = $currentBookings >= $event->capacity;
        
        return view('events.show', compact('event', 'userBooking', 'currentBookings', 'isEventFull'));
    }

    // below is the methods for each crud functionality
    public function create()
    {
        //Check if the user is organiser. Only organisers can create events
        if (auth()->user()->user_type !== 'organiser'){
            return redirect()->route('home')->with('error', 'Only organisers may create events');
        }

        // Get all the active categories for the create form
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('events.create', compact('categories')); //Pass categories to view
    }
    
    // Store a new event
    public function store(Request $request)
    {
        //Check if user is an organiser
        if (auth()->user()->user_type !== 'organiser'){
            return redirect()->route('home')->with('error', 'Only organisers are able to create events');
        }

        // Validation rules, using validate
        // Laravel throws a ValidationException error and stops the code if rules are not met
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'event_date' => 'required|date|after:today',
            'event_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:1000',

            // category features
            'categories' => 'array', //categories must be in arrays,
            'categories.*' => 'exists:categories,id' // Each category ID must exist in the category table

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

        //Event category feature:, attach selected categories to the event, which create records in the event_category pivot table
        if ($request->has('categories')) {
            $event->categories()->attach($request->categories);
        }

        return redirect()->route('events.show', $event)->with('success', 'Event created successfully!');
    }

    // Show edit form (event owner only)
    public function edit(Event $event)
    {
        // Only the event owner can access the edit form
        if ($event->organiser_id !== auth()->id()) {
            return redirect()->route('events.show', $event)->with('error', 'You can only edit your own events.');
        }

        //Get all active categories for the edit form
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        //Get the currently selected category IDs for the event
        $selectedCategories = $event->categories->pluck('id')->toArray();

        return view('events.edit', compact('event', 'categories', 'selectedCategories'));
    }

    // Update event
    public function update(Request $request, Event $event)
    {
        // Only the event owner can update
        if ($event->organiser_id !== auth()->id()) {
            return redirect()->route('events.show', $event)->with('error', 'only the owner can update event details.');
        }

        // Validation rules for each of part of the incoming request data
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string|max:1000',
            'event_date' => 'required|date|after:today',
            'event_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1|max:1000',
            // Category Feature : Also need to validate the categories
            'categories' => 'array', 
            'categories.*' => 'exists:categories,id'   
        ]);

        // Update the event
        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'],
            'location' => $validated['location'],
            'capacity' => $validated['capacity']
        ]);

        // CATEGORY FEATURE: Sync categories with the event
        // sync() removes all existing category relationships and adds the new ones
        // Using the ?? [] ensures empty array if no categories are selected, and also would remove all categories
        $event->categories()->sync($request->categories ?? []);
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
    // Middleware handles the authentication check
    $user = Auth::user();

     // Redirect attendees away from dashboard
    if ($user->user_type !== 'organiser') {
        return redirect()->route('home')->with('error', 'Only organisers can access the dashboard.');
    }
    // Raw SQL query for the organiser dashboar using DB::select() with parameter binding, 
    // LEFT JOIN is to ensure that events with zero bookings are also included 
    // Separate logic for organisers and attendees
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
    ", [$user->id]);
    

    $totalBookings = Booking::where('user_id', $user->id)->count();
    
    return view('dashboard', compact('events', 'totalBookings'));
    }
  

}



