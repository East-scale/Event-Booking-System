<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">{{ $event->title }}</h2>
        <a href="{{ route('home') }}">← Back to Events</a>
    </x-slot>
    
    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto px-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                
                <!-- Success and Error Messages -->
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
                
                <!-- Event Title and Details-->
                <h1 class="text-2xl font-bold mb-6">{{ $event->title }}</h1>
                
                 <div class="mb-6">
                    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</p>
                    <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($event->event_time)->format('g:i A') }}</p>
                    <p><strong>Location:</strong> {{ $event->location }}</p>
                    <p><strong>Capacity:</strong> {{ $event->capacity }} people</p>
                    <p><strong>Available Spots:</strong> 
                        @if($isEventFull)
                            <span class="text-red-600">Sold Out</span>
                        @else
                            <span class="text-green-600">{{ $event->capacity - $currentBookings }} remaining</span>
                        @endif
                    </p>
                    <p><strong>Organiser:</strong> {{ $event->user->name }}</p>
                </div>
                <!-- Description-->
                @if($event->description)
                    <div class="mb-6">
                        <h3 class="font-bold mb-2"> About This Event </h3>
                        <p> {{ $event->description}}</p>
                    </div>
                @endif   

                <!-- Action buttons-->
                <div class="border-t pt-6 mt-6">
                    @guest
                        <!--Not logged in-->
                        <div class="text-center">
                            <p class="mb-4">Please log in to book this event</p>
                            <a href="{{ route('login') }}" class="bg-blue-600 text-white px-6 py-2 rounded">Login</a>
                            <a href="{{ route('register')}}" class="bg-gray-600 text-white px-6 py-2 rounded ml-2">Register</a>
                        </div>
                    
                    @else
                    @if(auth()->user()->user_type === 'organiser')
                        <!-- Organiser viewing -->
                        @if($event->organiser_id === auth()->id())
                            <!-- Own event -->
                            <div class="text-center">
                                <p class="mb-4 font-bold">You are the organiser of this event!</p>
                                <a href="{{ route('events.edit',$event) }}" class="bg-yellow-500 text-white px-6 py-2 rounded">Edit Event</a>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" class="inline ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded" 
                                            onclick="return confirm('Are you sure you want to delete this event?')">
                                        Delete Event
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Case of another organiser's event -->
                            <div class="text-center text-gray-600">
                                <p>You are not able to book events as an organiser</p>
                            </div>
                        @endif
                    @else 
                        <!-- Attendee viewing -->
                         @if($userBooking)
                            <!-- Already booked -->
                            <div class="text-center">
                                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                    <strong>You're registered for this event!</strong>
                                </div>
                                <form action="{{ route('bookings.destroy', $userBooking)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded"
                                            onclick="return confirm('Are you sure you want to cancel your booking?')">
                                        Cancel Booking
                                    </button>
                                </form>
                            </div>
                        @elseif($isEventFull)
                            <!-- Full Event Scenario -->
                            <div class="text-center">
                                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                    This event is sold out
                                </div>
                            </div>
                        @else
                            <!--Can book, event is not full -->
                            <div class="text-center">
                                <p class="mb-4">Available spots: {{$event->capacity - $currentBookings}}</p>
                                <form action="{{ route('bookings.store', $event) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded text-lg">
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
</x-app-layout>