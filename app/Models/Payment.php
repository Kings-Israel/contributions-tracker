<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'payment_type',
        'reference_id',
        'amount',
        'month'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
