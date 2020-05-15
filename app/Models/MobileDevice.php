<?php

namespace App\Models;

use App\User;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Model;

/** @mixin Eloquent */
class MobileDevice extends Model
{
    protected $fillable = ['user_id', 'device_type', 'device_token'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
