<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable =['room_number','room_type','floor_no','rent','smoking_zone','reservation_status','available_at','hotel_id'];
}
