document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.category-filter-btn');
    const eventsContainer = document.getElementById('events-container');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const categoryId = this.dataset.categoryId;
            const url = categoryId ? `/?category=${categoryId}` : '/';
            
            // Update active button
            filterButtons.forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700');
            });
            this.classList.remove('bg-gray-100', 'text-gray-700');
            this.classList.add('bg-blue-600', 'text-white');
            
            // Make AJAX request
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateEvents(data.events);
            });
        });
    });
    
    function updateEvents(events) {
    if (events.length === 0) {
        eventsContainer.innerHTML = '<div class="text-center py-12"><h3>No events found</h3><p>Check back later for new events</p></div>';
        return;
    }
    
    let html = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 px-4">';
    events.forEach(event => {
        html += `<div class="bg-white shadow-sm border border-blue-100 rounded-lg hover:shadow-md transition-shadow">
            <div class="p-6">`;
        
        // Category badges
        if (event.categories && event.categories.length > 0) {
            event.categories.forEach(category => {
                html += `<span class="px-2 py-1 rounded-full text-xs font-medium text-white mr-1" 
                         style="background-color: ${category.color}">
                    ${category.name}
                </span>`;
            });
        }
        
        html += `
                <h3 class="text-xl font-bold mt-3 mb-2">
                    <a href="/events/${event.id}">${event.title}</a>
                </h3>
                <div class="mt-3 text-sm text-gray-600 space-y-1">
                    <p><strong>Date:</strong> ${event.formatted_date}</p>
                    <p><strong>Time:</strong> ${event.formatted_time}</p>
                    <p><strong>Location:</strong> ${event.location}</p>
                    <p><strong>Availability:</strong> 
                        ${event.available_spots === 0 
                            ? '<span class="text-red-600">Sold Out</span>' 
                            : `<span class="text-green-600">${event.available_spots} spots available</span>`}
                    </p>
                </div>`;
        
        // Description
        if (event.description) {
            const truncated = event.description.length > 100 
                ? event.description.substring(0, 100) + '...' 
                : event.description;
            html += `<p class="mt-2"><strong>Description:</strong> ${truncated}</p>`;
        }
        
        html += `</div></div>`;
    });
    html += '</div>';
    eventsContainer.innerHTML = html;
}
});