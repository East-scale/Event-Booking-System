<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">Edit Event</h2>
        <a href="{{ route('events.show', $event) }}">Back to Event</a>
    </x-slot>
    
    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto px-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                
                <!-- Validation Errors -->
                @if($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('events.update', $event )}}" method="POST">
                    @csrf
                    @method('PATCH')                   
                    <!-- Form fields are within here -->
                    <!--Event Title -->
                    <div class="mb-4">
                        <label for="title">Event Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title', $event->title) }}" maxlength="100"
                        class="w-full px-3 py-2 border rounded" required>
                    </div>
                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description">Description</label>
                        <textarea id="description" 
                        name="description" 
                        rows="4" 
                        maxlength="1000" 
                        class="w-full px-3 py-2 border rounded"> {{ old('description', $event->description) }}</textarea>
                    </div>

                    <!-- Date and Time -->
                    <div class="mb-4">
                        <label for="event_date">Event Date *</label>
                        <input type="date" 
                            id="event_date" 
                            name="event_date" 
                            value="{{ old('event_date', $event->event_date) }}"
                            min="{{ date('Y-m-d', strtotime('tomorrow')) }}"
                            class="w-full px-3 py-2 border rounded"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="event_time">Event Time *</label>
                        <input type="time" 
                            id="event_time" 
                            name="event_time" 
                            value="{{ old('event_time', $event->event_time) }}"
                            class="w-full px-3 py-2 border rounded"
                            required>
                    </div>

                    <!-- Location -->
                    <div class="mb-4">
                        <label for="location">Location *</label>
                        <input type="text" 
                            id="location" 
                            name="location" 
                            value="{{ old('location', $event->location) }}"
                            maxlength="255"
                            class="w-full px-3 py-2 border rounded"
                            required>
                    </div>

                    <!-- Capacity -->
                    <div class="mb-4">
                        <label for="capacity">Capacity *</label>
                        <input type="number" 
                            id="capacity" 
                            name="capacity" 
                            value="{{ old('capacity', $event->capacity) }}"
                            min="1" 
                            max="1000"
                            class="w-full px-3 py-2 border rounded"
                            required>
                    </div>

                    <!-- Categories -->
                    <div class="mb-4">
                        <label>Event Categories (Optional)</label>
                        @foreach($categories as $category)
                            <div class="mb-2">
                                <input type="checkbox" 
                                    name="categories[]" 
                                    value="{{ $category->id }}"
                                    id="category_{{ $category->id }}"
                                    {{ (old('categories') && in_array($category->id, old('categories'))) || 
                                        (!old('categories') && in_array($category->id, $selectedCategories)) ? 'checked' : '' }}>
                                <label for="category_{{ $category->id }}">
                                    <span class="inline-block w-3 h-3 rounded-full" style="background-color: {{ $category->color }}"></span>
                                    {{ $category->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-4 pt-4 border-t">
                        <a href="{{ route('home')}}" class="bg-gray-500 text-white px-6 py-2 rounded">Cancel</a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">Update Event</button>
                    </div>      
                                        
                </form>
            </div>
        </div>
    </div>
</x-app-layout>