<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'amount',
        'payment_method',
        'transaction_reference',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}
