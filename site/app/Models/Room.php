<?php

namespace App\Models;

use App\Models\Concerns\HasMediaUrls;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Room extends Model
{
    use HasMediaUrls;

    protected $guarded = [];

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
