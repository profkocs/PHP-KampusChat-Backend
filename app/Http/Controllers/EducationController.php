<?php
namespace App\Http\Controllers;
use App\University;
use App\University_Departments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


/**
* Created By Burak on 09/10/2019
* Bu Sınıf Kullanıcının Kurum Bilgisi Alması İçin Gereken İşlemleri İçeriyor
**/

class EducationController extends BaseController{

/**
* universities() -> Sistemde ki tüm üniversiteleri getiren fonksiyon
* return $result -> id,name,email_type
**/


 public function universities(){

 try{
   $result = University::all();
   return $this->sendResponse($result);
 }
 catch(\Exception $exception){
   return $this->sendError($exception->getMessage());
 }


 }

 /**
 * departments() -> Sistemde ki istenilen üniversiteye ait bölümleri  getiren fonksiyon
 * @param $id -> Sistemde Kayıtlı Olan Üniversite id si
 * return result -> id,name
 **/


public function departments($id){

try{
    $result = University::find($id)->departments;
    return $this->sendResponse($result);
}

catch(\Exception $exception){
  return $this->sendError($exception->getMessage());
}

}


}

 ?>
