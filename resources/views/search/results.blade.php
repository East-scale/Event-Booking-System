<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl">
                Search Results
            </h2>
            <a href="{{ route('search.index')}}" class="bg-blue-600 text-white px-4 py-2 rounded">
                New Search
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Search Summary -->
            <div class="bg-white border p-4 mb-6">
                <p> Found {{ $events->count() }}
                @if(request('query'))
                    matching " {{request('query')}}"
                @endif
                </p>
            </div>

    <!-- Used code from homepage -->
    @if($events->count() > 0)
        
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
                            <!-- Use Carbon to retrieve the event details -->
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
            <h3> No Events Found </h3>
            <p> Check back later for new events </p>
        </div>
    @endif
                
        </div>
    </div>

</x-app-layout>