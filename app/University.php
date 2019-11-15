<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    //
protected $table = "universities";
protected $fillable = ['id','name'];


    public function departments(){

      return $this->belongsToMany('App\Department','university_departments','university_id','department_id');

    }



}
