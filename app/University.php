<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

/**
* Created By Burak on 09/10/2019
**/

class University extends Model
{
    //

   protected $table = "universities";

   protected $fillable = [
       'id','name'
   ];


   /**
   * departments() -> Üniversiteye Ait Olan Tüm Bölümleri Getiren Fonksiyon
   **/

    public function departments(){

      return $this->belongsToMany('App\Department','university_departments','university_id','department_id');

    }


}
