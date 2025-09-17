<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// was empty initially
class Event extends Model
{
    use HasFactory;

    // The attributes that are mass assignable

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'location',
        'capacity',
        'organiser_id',
    ];

    // Get the attributes that should be cast
    protected function casts(): array
    {
        return [
            'event_date' => 'datetime',
            'event_time' => 'datetime'        
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Get the organiser who created the event
    public function organiser()
    {
        return $this->belongsTo(User::class, 'organiser_id');
    }

    // Define relation between events and bookings
    public function bookings()
    {
        // One event can have many bookings
        return $this->hasMany(Booking::class);
    }


    // Get the number of current bookings
    public function getCurrentBookingsCount(): int
    {
        return $this->bookings()->count();
    }

    //Users who have booked the event

    public function attendees()
    {
        return $this->belongsToMany(user::class, 'bookings');
    }
    
    // check if event is upcoming, using Carbon, and as boolean
    public function isUpcoming(): bool
    {
        return $this->event_date > Carbon::now();
    }


    // Get the number of available spots
    public function getAvailableSpots(): int
    {
        return $this->capacity - $this->getCurrentBookingsCount();
    }

  
    //Check if the event is full
    public function isFull(): bool
    {
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
        return $this->bookings()->where('user_id', $userId)->exists();
    }

    /**
     * Scope to get only upcoming events
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>', Carbon::now());
    }

}
