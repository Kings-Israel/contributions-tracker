<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the name
     *
     * @param  string  $value
     * @return string
     */
    public function getNameAttribute($value)
    {
        if ($value) {
            return Str::mask($value, '*', 3, 9);
        }
    }

    /**
     * Get the phone number
     *
     * @param  string  $value
     * @return string
     */
    public function getPhoneNumberAttribute($value)
    {
        if ($value) {
            return Str::mask($value, '*', 3, 9);
        }
    }
}
