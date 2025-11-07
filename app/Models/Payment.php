<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'stripe_session_id',
        'transaction_id',
        'user_id',
        'amount',
        'currency',
        'status',
    ];
}
