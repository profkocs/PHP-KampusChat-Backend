<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $table = "events";

    protected $fillable = [
        'user_id','group','shuffle_count','is_online'
    ];

}
