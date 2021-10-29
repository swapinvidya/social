<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacebookPage extends Model
{
    protected $fillable =[
        'user_id',
        'page_id',
        'token_id',
        'page_token',
        'image',
        'name',
        'provider',
    ];

}
