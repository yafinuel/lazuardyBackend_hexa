<?php

namespace App\Models;

use App\Shared\Enums\PayoutStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $fillable = [
        'tutor_id',
        'payout_number',
        'xendit_id',
        'amount',
        'bank_code',
        'account_holder_name',
        'account_number',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'payload_raw'
    ];

    protected $casts = [
        'payload_raw' => 'array',
        'approved_at' => 'datetime',
        'status' => PayoutStatusEnum::class,
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'tutor_id', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
