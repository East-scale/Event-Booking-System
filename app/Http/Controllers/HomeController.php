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
        $selectedCategory = null;
        if ($request->has('category') && $request->category!== 'all'){
            $selectedCategory = Category::find($request->category);
        }
        
        // Build the query with category filtering
        $eventsQuery = Event::with(['organiser', 'categories'])  
            ->upcoming()
            ->orderBy('event_date', 'asc') 
            ->orderBy('event_time', 'asc');
        
        //Apply category filter IF a category is selected, using whereHas to filter events
        if ($selectedCategory) {
            \Log::info('Filtering by category: ' . $selectedCategory->id);
            $eventsQuery->whereHas('categories', function ($query) use ($selectedCategory) {
                $query->where('categories.id', $selectedCategory->id);
            });
        }
        
        $events = $eventsQuery->simplePaginate(8); //paginate 8 events per page
        \Log::info('Events found: ' . $events->count());

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
        $availableSpots = $event->capacity - $event->bookings()->count();
        
        return [
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'event_date' => $event->event_date,
            'event_time' => $event->event_time,
            'location' => $event->location,
            'capacity' => $event->capacity,
            'organiser_name' => $event->organiser->name,
            'formatted_date' => Carbon::parse($event->event_date)->format('M j, Y'),
            'formatted_time' => Carbon::parse($event->event_time)->format('g:i A'),
            'available_spots' => $availableSpots,
            'categories' => $event->categories->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'color' => $category->color
                ];
            })->toArray()
        ];
    });
    
    return response()->json([
        'success' => true,
        'events' => $eventsData,
        'selected_category' => $selectedCategory,
        'events_count' => $events->count()
    ]);
}
}
