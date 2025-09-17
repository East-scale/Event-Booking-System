<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Defining the attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type', // additional attribute
    ];

    
    // The attributes that should be hidden for serialization.
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Check if the user is an organiser - return boolean
    public function isOrganiser(): bool
    {
        return $this->user_type === 'organiser';
    }

    // Check if the user is an attendee, return boolean
    public function isAttendee(): bool
    {
        return $this->user_type === 'attendee';
    }

    /**
     * Events created by this organiser
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function organizedEvents()
    {
        return $this->hasMany(Event::class, 'organiser_id');
    }

    
    // Bookings made by this attendee
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

      /**
     * Events this user has booked
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function bookedEvents()
    {
        return $this->belongsToMany(Event::class, 'bookings');
    }

}
