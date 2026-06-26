<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $guarded = [];

    protected $casts = [
        'active'                  => 'boolean',
        'accepts_commitment_fee'  => 'boolean',
    ];
}
