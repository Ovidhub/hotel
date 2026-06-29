<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class IcalFeed extends Model
{
    protected $guarded = [];

    protected $casts = [
        'last_synced_at' => 'datetime',
    ];

    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }
}
