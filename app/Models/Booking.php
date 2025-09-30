<?php

namespace App\Models; //location

use Illuminate\Database\Eloquent\Factories\HasFactory; // additional one
use Illuminate\Database\Eloquent\Model;

// Booking model represents a user booking for an event 
class Booking extends Model
{
    use HasFactory;

    // Use fillable property to declare the attributes that are mass assignable.
    // Is needed as Laravel blocks mass assignment by default for security
    protected $fillable = [ 'user_id','event_id', ];
   

    //Eloquent relationship definitions
    //Booking belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

    //Booking belongs to one event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
