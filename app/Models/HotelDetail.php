<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HotelDetail extends Model
{
    protected $fillable = ['country','city','state','location','zip_code','hotel_id'];
}
