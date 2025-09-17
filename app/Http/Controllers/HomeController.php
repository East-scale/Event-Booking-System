<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::upcoming()
            ->orderBy('event_date', 'asc') // date ascending order
            ->orderBy('event_time', 'asc')
            ->simplePaginate(8); // HAD TO SKIP PAGINATION, IT WOULD NOT WORK, HAVE TO FIX
        
        return view('home', compact('events')); //view is called 'home', and uses compact
    }
}
