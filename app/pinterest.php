<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pinterest extends Model
{
    protected $fillable = ['user_id', 'token'];
}
