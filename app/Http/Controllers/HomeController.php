<?php
namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get all active categories for the filter buttons
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        // Get selected category from URL parameter
        $selectedCategory = $request->get('category');
        
        // Build the query with category filtering
        $eventsQuery = Event::with(['organiser', 'categories'])  // FIXED: Changed $events to $eventsQuery
            ->upcoming()
            ->orderBy('event_date', 'asc') // date ascending order
            ->orderBy('event_time', 'asc');
        
        //Apply category filter IF a category is selected, using whereHas to filter events
        if ($selectedCategory && $selectedCategory !== 'all') {
            $eventsQuery->whereHas('categories', function ($query) use ($selectedCategory) {
                $query->where('categories.id', $selectedCategory);
            });
        }
        
        $events = $eventsQuery->simplePaginate(8);
        
        // Return a JSON response for AJAX requests
        if ($request->ajax()) {
            return $this->getEventsJsonResponse($events, $selectedCategory);
        }

        //return home.blade.php, passes events, categories, selectedCategories to view
        return view('home', compact('events', 'categories', 'selectedCategory'));
    }

    private function getEventsJsonResponse($events, $selectedCategory = null): JsonResponse
    {
        $eventsData = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'event_date' => $event->event_date,
                'event_time' => $event->event_time,
                'location' => $event->location,
                'max_attendees' => $event->max_attendees,
                'price' => $event->price,
                'organiser_name' => $event->organiser->name, // Using your 'organiser' relationship
                'formatted_date' => Carbon::parse($event->event_date)->format('M j, Y'),
                'formatted_time' => Carbon::parse($event->event_time)->format('g:i A'),
                'formatted_price' => $event->price > 0 ? '$' . number_format($event->price, 2) : 'Free',
                'categories' => $event->categories ? $event->categories->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'color' => $category->color
                    ];
                }) ->toArray() : [] // ensure that empty array is returned if there is no categories
            ];
        });

        return response()->json([
            'success' => true,
            'events' => $eventsData,
            'selected_category' => $selectedCategory,
            'events_count' => $events->count(),
            'message' => $events->count() > 0
                ? "{$events->count()} events were found"
                : "No events were found for the selected criteria"
        ]);
    }
}
