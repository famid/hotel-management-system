<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class MobileDevice extends Model
{
    protected $fillable = ['user_id', 'device_type', 'device_token'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
