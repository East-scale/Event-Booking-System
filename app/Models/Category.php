<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;
    /**
     * Define the mass assignable attributes
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'color',
        'is_active'
    ];

    /**
     * The attributes to cast
     * @var array<string, string>
     */
    protected $casts = [
        'is_active'=>'boolean', // ensure that is_active is a boolean
    ];

    /**
     * Get events that belong to this category, returns a BelongstoMany object
     * There is a many to many relationship between the category model and the event model
     * @return BelongsToMany
     */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_category');
    }

    /**
     * Defining of a usable query scope to filter the categories where is_active is true
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

     public function categoryActive($query)
     {
        return $query->where('is_active', true);
     }
    
}
