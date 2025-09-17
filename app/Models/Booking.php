<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // additional one
use Illuminate\Database\Eloquent\Model;

// Was empty initially 

class Booking extends Model
{
    use HasFactory;

    // The attributes that are mass assignable.
    protected $fillable = [
        'user_id',
        'event_id',
    ];
   
    //  Below is how this booking is connected to other models

    // The user who made this booking
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // The event that was booked
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
