<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// Event class representing an entity in the application
class Event extends Model
{
    // use hasfactory to enable factory-based model creation for testing and seeding
    use HasFactory;

    //Define the attributes of events that are mass assignable
    protected $fillable = [ 'title', 'description', 'event_date', 'location', 'capacity', 'organiser_id', ];

    // Define how these attributes that should be cast when retrieved or set (datetime)
    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'event_time' => 'datetime'        
        ];
    }
 

    public function user()
    {
        return $this->belongsTo(User::class, 'organiser_id');
    }

    // Get the organiser who created the event, defines a 1-to-1 relationship with the user model, with organiser ID as the foreign key
    public function organiser()
    {
        return $this->belongsTo(User::class, 'organiser_id');
    }

    // Define relation between events and bookings
    public function bookings()
    {
        // One event can have many bookings, establishes one to many relationship with the booking model
        return $this->hasMany(Booking::class);
    }


    // Get the number of current bookings as integer
    public function getCurrentBookingsCount(): int
    {
        //use the bookings relationship to count all the associated bookings
        return $this->bookings()->count();
    }

    //Define relation between users and bookings
    public function attendees()
    {
        //establishes a many-to-many relationship between bookings and users
        return $this->belongsToMany(user::class, 'bookings');
    }
    
    // check if event is upcoming, using Carbon, and as boolean
    public function isUpcoming(): bool
    {
        // use carbon to get the current date and time, and compare with event date, returning a boolean
        return $this->event_date > Carbon::now();
    }


    // Get the current number of available spots by subtracting count of current bookings from event capacity
    public function getAvailableSpots(): int
    {
        return $this->capacity - $this->getCurrentBookingsCount();
    }

  
    //Check if the event is full
    public function isFull(): bool
    {
        // check if count of available spots is greater than zero
        return $this->getAvailableSpots() <= 0;
    }

    /**
     * Check if a specific user has booked this event
     *
     * @param int $userId
     * @return bool
     */
    public function isBookedByUser(int $userId): bool
    {
        //queries the bookings relationship to check for a booking containing the given user ID, returning boolean
        return $this->bookings()->where('user_id', $userId)->exists();
    }

    /**
     * Scope to get only upcoming events
     *. This uses the query builder instance and the modified query builder
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        //add a where clause to the query to filter events with a date later than now
        return $query->where('event_date', '>', Carbon::now());
    }

    // Add this method to your existing Event model class
    /**
     * Get the categories that belong to this event.
     *
     * @return BelongsToMany
     */
    //Define a many to many relationship between the Event and Category models, 
    // NEeded to specify here the table name
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'event_category');
    }

    /**
     * Get a comma separate string of category names for an event
     * @return string
     */
    public function getCategoryNamesAttribute(): string
    {
        return $this->categories->pluck('name')->join(', ');
    }
}
