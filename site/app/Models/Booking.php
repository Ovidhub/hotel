<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Booking extends Model
{
    // All booking mutations pass explicit, server-computed arrays — never request()->all().
    protected $fillable = [
        'ref',
        'bookable_type',
        'bookable_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'check_in',
        'check_out',
        'nights',
        'guests',
        'total',
        'commitment_percent',
        'commitment_fee',
        'balance_due',
        'status',
        'payment_method_id',
        'proof_path',
        'notes',
    ];

    protected $casts = [
        'check_in'       => 'date',
        'check_out'      => 'date',
    ];

    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
