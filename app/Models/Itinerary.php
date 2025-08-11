<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Itinerary extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'enquiry_id',
        'title',
        'notes',
        'days',
    ];

    protected $casts = [
        'days' => 'array',
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }
}
