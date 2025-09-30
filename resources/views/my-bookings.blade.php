<x-app-layout>
    <x-slot name="header">
        <h2>
            My Bookings
        </h2>
    </x-slot>
    
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success and error messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{session('success') }}
                </div>
            @endif
            
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{session('error') }}
                </div>
            @endif
            
            <!-- Main content wrapper -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold">My Event Bookings</h3>
                        <div>
                            Total: <span class="font-bold">{{ count($bookings) }}</span>
                        </div>
                    </div>
                    
                    <!-- Bookings content goes here -->
                    @if(count($bookings)> 0)
                        <div>
                            @foreach($bookings as $booking)
                                <div class="bg-white border p-4 mb-4">
                                    <h4>{{ $booking->title }}</h4>
                                    
                                    <p>Date: {{ \Carbon\Carbon::parse($booking->date)->format('M d, Y g:i A') }}</p>
                                    <p>Organiser: {{ $booking->organiser_name }}</p>
                                    <p>Capacity: {{ $booking->capacity }} people</p>
                                    
                                    @if($booking->description)
                                        <p>Description: {{Str::limit($booking->description, 100) }}</p>
                                    @endif
                                    
                                    <a href="{{route('events.show', $booking->event_id) }}">View Event</a>
                                </div>
                            @endforeach
                        </div>

                    @else
                        <!-- Condition for no bookings -->    
                        <div>
                            <h3> You have not yet made any bookings</h3>
                            <p> Follow the link to explore available events </p>
                            <a href="{{route('home') }}"> Browse Events</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>