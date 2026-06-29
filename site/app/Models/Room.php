<?php

namespace App\Models;

use App\Models\Concerns\HasIcalToken;
use App\Models\Concerns\HasMediaUrls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Room extends Model
{
    use HasMediaUrls;
    use HasIcalToken;

    protected $guarded = [];

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function availabilityBlocks(): MorphMany
    {
        return $this->morphMany(AvailabilityBlock::class, 'bookable');
    }

    protected $casts = [
        'gallery'   => 'array',
        'amenities' => 'array',
        'includes'  => 'array',
        'policies'  => 'array',
        'best_for'  => 'array',
        'is_active' => 'boolean',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function priceFormatted(): Attribute
    {
        return Attribute::get(fn () => '₦' . number_format($this->price));
    }
}
