<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Group extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tags' => 'array',
    ];

    /**
     * Get all of the members for the Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function members(): HasManyThrough
    {
        return $this->hasManyThrough(Member::class, GroupMember::class, 'group_id', 'id', 'id', 'member_id');
    }
}
