<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'midtrans_order_id',
        'midtrans_snap_token',
        'amount',
        'payment_type',
        'payment_method',
        'status',
        'payment_date',
        'proof_image',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
