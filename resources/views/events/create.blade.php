<!-- Brand new AI generated html -->
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create New Event
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
                    <!-- Display Validation Errors -->
                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('events.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Event Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Event Title *
                            </label>
                            <input type="text" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                   maxlength="100"
                                   required>
                        </div>

                        <!-- Event Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                      maxlength="1000"
                                      placeholder="Tell people what your event is about...">{{ old('description') }}</textarea>
                        </div>

                        <!-- Date and Time Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    Event Date *
                                </label>
                                <input type="date" 
                                       id="event_date" 
                                       name="event_date" 
                                       value="{{ old('event_date') }}"
                                       min="{{ date('Y-m-d', strtotime('tomorrow')) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>

                            <div>
                                <label for="event_time" class="block text-sm font-medium text-gray-700 mb-2">
                                    Event Time *
                                </label>
                                <input type="time" 
                                       id="event_time" 
                                       name="event_time" 
                                       value="{{ old('event_time') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       required>
                            </div>
                        </div>

                        <!-- Location and Capacity Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                    Location *
                                </label>
                                <input type="text" 
                                       id="location" 
                                       name="location" 
                                       value="{{ old('location') }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       maxlength="255"
                                       placeholder="Where will your event take place?"
                                       required>
                            </div>

                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">
                                    Capacity *
                                </label>
                                <input type="number" 
                                       id="capacity" 
                                       name="capacity" 
                                       value="{{ old('capacity') }}"
                                       min="1" 
                                       max="1000"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Maximum attendees"
                                       required>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4 pt-6 border-t">
                            <a href="{{ route('home') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded"
                               style="color: black !important;">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded"
                                    style="color: black !important;">
                                Create Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>