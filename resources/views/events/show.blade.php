{{-- This is currently completely AI generated page HTML --}}
{{-- Event Details Page with Functional Booking System --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $event->title }}
            </h2>
            <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                ← Back to Events
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-lg">
                <div class="p-8">
                    {{-- Success/Error Messages --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Event Title -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $event->title }}</h1>
                    
                    <!-- Event Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Date</p>
                                    <p class="font-medium">{{ \Illuminate\Support\Carbon::parse($event->event_date)->format('l, F j, Y') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Time</p>
                                    <p class="font-medium">{{ \Illuminate\Support\Carbon::parse($event->event_time)->format('g:i A') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Location</p>
                                    <p class="font-medium">{{ $event->location }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Capacity</p>
                                    <p class="font-medium">{{ $event->capacity }} people</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Available Spots</p>
                                    @if($event->isFull())
                                        <p class="font-medium text-red-600">Sold Out</p>
                                    @else
                                        <p class="font-medium text-green-600">{{ $event->getAvailableSpots() }} remaining</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-500">Organiser</p>
                                    <p class="font-medium">{{ $event->user ? $event->user->name : 'Unknown Organiser' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Event Description -->
                    @if($event->description)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">About This Event</h3>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($event->description)) !!}
                            </div>
                        </div>
                    @endif
                    
                    <!-- Action Buttons -->
                    <div class="border-t pt-6">
                        @guest
                            <!-- Guest Users -->
                            <div class="text-center">
                                <p class="text-gray-600 mb-4">Please log in to book this event</p>
                                <div class="space-x-4">
                                    <a href="{{ route('login') }}" 
                                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                        Login
                                    </a>
                                    <a href="{{ route('register') }}" 
                                       class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded">
                                        Register
                                    </a>
                                </div>
                            </div>
                        @else
                            @if(auth()->user()->user_type === 'organiser')
                                <!-- Organiser Users -->
                                @if($event->organiser_id === auth()->id())
                                    <!-- Event Owner -->
                                    <div class="text-center">
                                        <p class="text-blue-600 font-semibold mb-4">You are the organiser of this event</p>
                                        <div class="space-x-4">
                                            <a href="{{ route('events.edit', $event) }}" 
                                               class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded"
                                               style="color: green !important;"> 
                                               Edit Event
                                            </a>
                                            <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded"
                                                        style="color: red !important;"
                                                        onclick="return confirm('Are you sure you want to delete this event?')">
                                                    Delete Event
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <!-- Other Organisers -->
                                    <div class="text-center text-gray-600">
                                        <p>You cannot book events as an organiser.</p>
                                    </div>
                                @endif
                            @else
                                <!-- Attendee Users -->
                                @php
                                    // Check if user already booked this event
                                    $userBooking = App\Models\Booking::where('organiser_id', auth()->id())
                                                                  ->where('event_id', $event->id)
                                                                  ->first();
                                    $currentBookings = App\Models\Booking::where('event_id', $event->id)->count();
                                    $isEventFull = $currentBookings >= $event->capacity;
                                @endphp

                                @if ($userBooking)
                                    <!-- User already booked -->
                                    <div class="text-center">
                                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                            <strong>You're registered for this event!</strong>
                                        </div>
                                        <p class="text-gray-600 mb-4">Booking confirmed on {{ $userBooking->created_at->format('M j, Y') }}</p>
                                        <form action="{{ route('bookings.destroy', $userBooking) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-6 rounded"
                                                    style="color:red !important;"
                                                    onclick="return confirm('Are you sure you want to cancel your booking?')">
                                                Cancel Booking
                                            </button>
                                        </form>
                                    </div>
                                @elseif (!$event->isUpcoming())
                                    <!-- Event has passed -->
                                    <div class="text-center">
                                        <div class="bg-gray-100 border border-gray-400 text-gray-700 px-4 py-3 rounded">
                                            This event has already passed
                                        </div>
                                    </div>
                                @elseif ($isEventFull)
                                    <!-- Event is full -->
                                    <div class="text-center">
                                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                            This event is sold out ({{ $currentBookings }}/{{ $event->capacity }})
                                        </div>
                                    </div>
                                @else
                                    <!-- Show Book Now button -->
                                    <div class="text-center">
                                        <p class="text-gray-600 mb-4">Available spots: {{ $event->capacity - $currentBookings }}/{{ $event->capacity }}</p>
                                        <form action="{{ route('bookings.store', $event) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg text-lg"
                                                    style="color: blue !important;">
                                                Book Now
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endif
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>