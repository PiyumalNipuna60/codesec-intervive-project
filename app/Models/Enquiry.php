<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'travel_start_date',
        'travel_end_date',
        'number_of_people',
        'preferred_destinations',
        'budget',
        'status',
        'assigned_to',
    ];

    protected $casts = [
        'preferred_destinations' => 'array',
        'travel_start_date' => 'date',
        'travel_end_date' => 'date',
    ];

    public function agent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function itineraries()
    {
        return $this->hasMany(Itinerary::class);
    }
}
