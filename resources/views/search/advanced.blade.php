<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">
            Advanced Event Search
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto px-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h3> Search for Events </h3>

                <form method ="GET" action="{{ route('search.results') }}">

                    <!-- Text Search -->
                    <div>
                        <label for="query">Search Events</label>
                        <input type="text" 
                               id="query" 
                               name="query" 
                               placeholder="Enter event title..." 
                               class="w-full px-3 py-2 border rounded">
                    </div>

                    <!-- Category Dropdown -->
                    <div>
                        <label for="category">Category</label>
                        <select id="category" name="category">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <!-- Buttons -->
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded">
                        Search Events
                    </button>
                    <a href="{{ route('home') }}" class="ml-4 text-blue-600">Back to Home</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>