<?php
namespace App\Http\Controllers;
use App\System_Banned;
use App\Banned;



class BanController extends BaseController{



  /**
  * checkBannedList() -> Kullanıcı Hesabının Sistem Tarafından Banlanıp Banlanmadığını Kontrol Eden Fonksiyon
  * @param  $id -> User Id sini  İçeren Değişken
  **/

/*
  public function checkBannedList($id){


  try{
  $result = System_Banned::where('user_id',$id)->where('end_date','=',null)->orWhere('end_date','>',date('Y-m-d'))->first(['end_date','updated_at']);
  if($result){

    return $this->sendError("Account Banned",$result);

  }
  return $this->sendResponse("OK");

  }
  catch(\Exception $exception){
        return $this->sendError("Exception",$exception->getMessage());
  }


  }

*/
public function banUser($user_id,$banned_user_id){

try{


$input['user_id'] = $user_id;
$input['banned_user_id'] = $banned_user_id;

Banned::create($input);
return $this->sendResponse('OK');

}
catch(\Exception $exception){
      return $this->sendError("Exception",$exception->getMessage());
}


}






}

 ?>
