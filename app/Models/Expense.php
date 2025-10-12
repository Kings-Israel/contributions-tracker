<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Expense extends Model
{
    /** @use HasFactory<\Database\Factories\ExpenseFactory> */
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['can_approve', 'can_edit'];

    /**
     * Get the status
     *
     * @param  string  $value
     * @return string
     */
    public function getStatusAttribute($value)
    {
        return Str::title($value);
    }

    /**
     * Get the attachment
     *
     * @param  string  $value
     * @return string
     */
    public function getAttachmentAttribute($value)
    {
        if ($value) {
            return asset('storage/attachments/'.$value);
        }

        return null;
    }

    /**
     * Get the canApprove
     *
     * @return bool
     */
    public function getCanApproveAttribute()
    {
        if (auth()->check()) {
            if ($this->status === 'Pending' && $this->user_id !== auth()->id()) {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * Get the canEdit
     *
     * @return bool
     */
    public function getCanEditAttribute()
    {
        if (auth()->check()) {
            if (($this->status === 'Pending' || $this->status === 'Rejected') && $this->user_id === auth()->id()) {
                return true;
            }

            return false;
        }

        return false;
    }

    /**
     * Get the user that owns the Expense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the approvedBy that owns the Expense
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }
}
