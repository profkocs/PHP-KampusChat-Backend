<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $table = 'codes';
    //

    protected $fillable = [
        'email', 'code', 'revoked','type'
    ];

}
