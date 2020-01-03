<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    //

    protected $fillable =
        [
            'user_id', 'banned_user_id'
        ];
}
