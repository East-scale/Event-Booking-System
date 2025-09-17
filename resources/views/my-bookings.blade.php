{{-- resources/views/bookings/my-bookings.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Success/Error Messages --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold">My Event Bookings</h3>
                        <div class="text-sm text-gray-600">
                            Total Bookings: <span class="font-bold">{{ count($bookings) }}</span>
                        </div>
                    </div>

                    @if(count($bookings) > 0)
                        <div class="grid gap-6">
                            @foreach($bookings as $booking)
                                @php
                                    $eventDate = \Carbon\Carbon::parse($booking->date);
                                    $isUpcoming = $eventDate->isFuture();
                                    $isPast = $eventDate->isPast();
                                @endphp
                                
                                <div class="bg-gray-50 rounded-lg p-6 border-l-4 
                                    {{ $isUpcoming ? 'border-green-500' : 'border-gray-400' }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-semibold text-gray-900 mb-2">
                                                {{ $booking->title }}
                                            </h4>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                                <div>
                                                    <p><strong>Date:</strong> {{ $eventDate->format('F j, Y g:i A') }}</p>
                                                    <p><strong>Organiser:</strong> {{ $booking->organiser_name }}</p>
                                                    <p><strong>Capacity:</strong> {{ $booking->capacity }} people</p>
                                                </div>
                                                <div>
                                                    <p><strong>Booked on:</strong> 
                                                        {{ \Carbon\Carbon::parse($booking->booked_at)->format('M j, Y') }}
                                                    </p>
                                                    <p><strong>Status:</strong> 
                                                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                            {{ $isUpcoming ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                            {{ $isUpcoming ? 'Upcoming' : 'Past' }}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>

                                            @if($booking->description)
                                                <div class="mt-3">
                                                    <p class="text-sm text-gray-700">
                                                        <strong>Description:</strong> 
                                                        {{ Str::limit($booking->description, 150) }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="ml-4 flex flex-col space-y-2">
                                            <a href="{{ route('events.show', $booking->event_id) }}" 
                                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                                                View Event
                                            </a>
                                            
                                            @if($isUpcoming)
                                                <form action="{{ route('bookings.cancel', $booking->booking_id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to cancel this booking?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full">
                                                        Cancel Booking
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Booking Summary --}}
                        <div class="mt-8 bg-blue-50 rounded-lg p-4">
                            <h4 class="font-semibold text-blue-900 mb-2">Booking Summary</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-blue-700">Total Events:</span>
                                    <span class="font-bold ml-2">{{ count($bookings) }}</span>
                                </div>
                                <div>
                                    <span class="text-green-700">Upcoming Events:</span>
                                    <span class="font-bold ml-2">
                                        {{ collect($bookings)->filter(function($booking) {
                                            return \Carbon\Carbon::parse($booking->date)->isFuture();
                                        })->count() }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-gray-700">Past Events:</span>
                                    <span class="font-bold ml-2">
                                        {{ collect($bookings)->filter(function($booking) {
                                            return \Carbon\Carbon::parse($booking->event_date)->isPast();
                                        })->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    @else
                        <div class="text-center py-12">
                            <div class="text-gray-500 text-lg mb-4">You haven't booked any events yet</div>
                            <p class="text-gray-400 mb-6">Start exploring events and make your first booking!</p>
                            <a href="{{ route('events.index') }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg"
                               style="background-color: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; display: inline-block; font-weight: bold;">
                                Browse Events
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>