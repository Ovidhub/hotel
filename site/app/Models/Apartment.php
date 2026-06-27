<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Apartment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'gallery'   => 'array',
        'amenities' => 'array',
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
