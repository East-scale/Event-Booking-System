<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ ('Home Page - Events') }}
            </h2>
            @auth
                @if(auth()->user()->isOrganiser())
                    <a href="{{route('dashboard')}}" 
                        Create Event
                    </a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Category sections TEST GIT -->
        <div class="bg-blue-50 border border-gray-200 rounded-lg mb-8 shadow-sm">
            <div class="px-6 py-6">
                <!-- Your title and description here -->
                <div>
                    <h2 class="text-xl font-bold mb-4"> Browse Events by Category </h3>
                    
                <div class="flex flex-wrap gap-3">
                    <!-- All Events button -->
                    <a href="{{ route('home') }}" 
                    data-category-id="" 
                    class="category-filter-btn {{ !$selectedCategory ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-blue-50' }} px-3 py-1 rounded-full text-sm">
                        All Event Categories
                    </a>

                    <!-- Category buttons loop -->
                    @foreach($categories as $category)
                        <a href="{{ route('home', ['category'=> $category->id]) }}" 
                        data-category-id="{{ $category->id }}"
                        class="category-filter-btn px-4 py-2 rounded-lg {{ $selectedCategory && $selectedCategory->id == $category->id ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-blue-50' }} px-3 py-1 rounded-full text-sm">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>     

        <!-- Events grid -->
    <div id="events-container">
    @if($events->count() > 0)
        <!-- Your events grid here -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-4">
            <!-- Your foreach loop for events here -->
            @foreach($events as $event)
                <div class="bg-white shadow-sm border border-blue-100 rounded-lg hover:shadow-md transition-shadow">
                    <div class="p-6">
                        @foreach($event->categories as $category)
                            <span class="px-2 py-1 rounded-full text-xs font-medium text-white"
                                style="background-color: {{ $category->color }}">
                                {{$category->name}}
                            </span>
                        @endforeach
                        <!-- Event Title -->
                        <h3 class="text-xl font-bold mt-3 mb-2">
                            <a href="{{route('events.show', $event)}}">
                                {{$event->title }}
                            </a>
                        </h3>

                        <!-- Event info with labels -->
                        <div class="mt-3 text-sm text-gray-600 space-y-1">
                            <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</p>
                            <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}</p>
                            <p><strong>Location:</strong> {{ $event->location }}</p>
                            <p><strong>Availability:</strong> 
                                @if($event->isFull())
                                    <span class="text-red-600">Sold Out</span>
                                @else
                                    <span class="text-green-600">{{$event->getAvailableSpots() }} spots available</span>
                                @endif
                            </p>
                        </div>

                        <!-- Description -->
                        @if($event->description)
                            <p><strong>Description:</strong> {{ Str::limit($event->description, 100) }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination  -->
        {{ $events->links() }}

    @else
        <div class="text-center py-12">
            <h3> No Upcoming Events </h3>
            <p> Check back later for new events </p>
        </div>

        <!-- Your 'no events found' message -->
        <p> No events were found for that criteria, please try another </p>

    @endif
    </div>

    </div>
</div>



</x-app-layout>

