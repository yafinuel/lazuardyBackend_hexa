<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'external_id',
        'xendit_id',
        'payment_method',
        'payment_channel',
        'amount',
        'status',
        'checkout_url',
        'paid_at',
        'payload_raw'
    ];

    protected $casts = [
        'payload_raw' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
