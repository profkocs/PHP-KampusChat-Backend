<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $fillable = ['id','chat_id','type','message','sender_user_id','is_seen'];
}
