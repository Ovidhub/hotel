<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BookingInquiry extends Model
{
    protected $guarded = [];
    protected $attributes = ['status' => 'new'];
}
