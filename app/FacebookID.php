<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacebookID extends Model
{
    protected $fillable = [
    'user_id',
    'fb_token'
    ];
}
