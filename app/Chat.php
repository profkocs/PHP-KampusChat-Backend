<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    //
    protected $fillable =
        [
            'id' ,'owner_user_id', 'guest_user_id' ,'is_checked'
        ];

}
