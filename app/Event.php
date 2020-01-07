<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //
    protected $table = "events";

    protected $fillable = [
        'user_id','group','is_online','updated_at'
    ];

}
