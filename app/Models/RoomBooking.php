<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomBooking extends Model
{
    protected $fillable =['check_in','check_out','payment_status','paid_amount','room_id','user_id'];
}
