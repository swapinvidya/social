<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'post',
        'post_text',
        'user_id',
        'response',
        'schedule',
        'file',
        'shorten',
        'media_url',
        'media_type',
        'provider'

    ];
}
