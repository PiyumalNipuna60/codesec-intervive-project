<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'itinerary_id',
        'title',
        'price_per_person',
        'currency',
        'notes',
        'is_final',
        'unique_id',
    ];

    public function itinerary()
    {
        return $this->belongsTo(Itinerary::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($quotation) {
            $quotation->unique_id = \Str::uuid();
        });
    }
}
