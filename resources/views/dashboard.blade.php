<x-app-layout>
    <x-slot name="header">
    <h2 class="font-semibold text-xl"> {{ auth()->user()->user_type === 'organiser' ? 'Organiser Dashboard' : 'Attendee Dashboard' }} </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4 mb-8">
            <!-- Total Events Card (keeping exact style from home page) -->
            <div class="bg-white shadow-sm border border-blue-100 rounded-lg p-6">
                <h3 class="text-lg font-bold mb-2">Total Events</h3>
                <!-- Used PHP count function -->
                <p>{{ count($events) }}</p> 
            </div>
            <!-- Total Bookings Card -->
            <div class="bg-white shadow-sm border border-blue-100 rounded-lg p-6">
                <h3>Total Bookings</h3>
                <p>{{ $totalBookings }}</p>
            </div>

            <!-- Available Spots Card -->
            <div class="bg-white shadow-sm border border-blue-100 rounded-lg p-6">
                <h3>Available Spots</h3>
                <p>{{ array_sum(array_column($events, 'remaining_spots')) }}</p>
            </div>

        </div>

        {{-- Events Section --}}
        <div class="bg-blue-50 border border-gray-200 rounded-lg shadow-sm">
            <div class="px-6 py-6">
            <!-- Available Spots Card -flexbox for placing -->
            <div class="flex justify-between items-center mb-6"> 
                <h3 class="text-xl font-bold">My Events</h3>
                <a href="{{ route('events.create') }}" 
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                    Create New Event
                </a>
            </div>
            @if(count($events) > 0)
            <div class="bg-white rounded-lg overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Event Title</th>
                            <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Date</th>
                            <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Capacity</th>
                            <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Bookings</th>
                            <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Remaining</th>
                            <th class="px-6 py-3 text-left text-sm font-bold text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($events as $event)
                            <tr class="border-b">
                                <td class="px-4 py-3">{{ $event->title }}</td>
                                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</td>
                                <td class="px-4 py-3">{{ $event->capacity }}</td>
                                <td class="px-4 py-3">{{ $event->current_bookings }}</td>
                                <td class="px-4 py-3">{{ $event->remaining_spots }}</td>
                                <td class="px-4 py-3">
                                    <a href="{{ route('events.show', $event->id) }}" class="text-blue-600 mr-2">View</a>
                                    <a href="{{ route('events.edit', $event->id) }}" class="text-blue-600 mr-2">Edit</a>
                                    <!-- Form is required for delete. CSRF is security token, Laravel needs delete for the destroy route, HTML forms only support GET/POST -->
                                    <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if(auth()->user()->user_type ==='organiser')
            <div class="text-center py-8">
                <p class="text-gray-600 mb-4">No events created yet</p>
                <a href="{{ route('events.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Create Your First Event
                </a>
            </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-600 mb-4">No upcoming events available</p>
                </div>
            @endif                        
                </div>
            </div>    
        </div>
    </div>
</x-app-layout>
