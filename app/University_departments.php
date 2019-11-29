<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class University_departments extends Model
{
    //

   protected $table = "university_departments";

   protected $fillable = [
       'id','university_id','department_id'
   ];

}
