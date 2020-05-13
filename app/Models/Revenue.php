<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    protected $fillable =['amount','hotel_id','room_id','user_id'];
}
