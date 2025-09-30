<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(){
        $categories = Category::where('is_active', true)->get();
        return view('search.advanced', compact('categories'));
    }

    public function search(Request $request)
    {
        $events = Event::with(['organiser', 'categories'])->upcoming();

        //Basic text (title) search
        if ($request->get('query')) {
            $events->where('title', 'LIKE', "%{$request->get('query')}%");
        }

        //Category filter
        if ($request->get('category')) {
            $events->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->get('category'));
            });
        }

        // 8 per page
        $events = $events->simplePaginate(8);
        $categories = Category::where('is_active', true)->get();

        return view('search.results', compact('events', 'categories'));
    }
    
}
