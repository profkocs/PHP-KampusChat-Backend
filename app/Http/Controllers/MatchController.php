<?php
namespace App\Http\Controllers;
use App\Match;
use App\User;
use App\Chat;



/**
* Created by Burak on 11/10/2019
* Bu Sınıf Kullanıcı Eşleşmelerinin  Kontrolünü Sağlayacak
**/

class MatchController extends BaseController {


/**
* createInformations() -> Eşleşme Tablosunda Kayıt Oluşturan Fonksiyon
* @param $user_id
* return "OK" -> İşlem Tamamlandı Bilgisi
**/

public function createInformations($user_id){

  // default degerler
  $input['user_id'] = $user_id;
  $input['group'] = 0;
  $input['shuffle_count'] = 3;
  $input['is_online'] = true;

 try{
  Match::create($input);
  return $this->sendResponse("OK");
 }catch(\Exception $exception){
   return $this->sendError("Exception",$exception->getMessage());
 }


}


/**
* updateInformations() -> Oturum Açıldığında Aktif olan ve Match Tablosu Bilgilerini Güncelleyen Fonksiyon
* updateInformations() -> Ve Güncel Bilgileri Cevap Olarak Dönderen Fonksiyon
* @param $user_id
* return $input['shuffle_count']
**/


public function updateInformations($user_id){

// defaults
$input['is_online'] = true;
$input['group'] = 0;
$input['shuffle_count'] = 3;

try{
 // Son Görülmeden İtibaren 1 Gün Geçmişsse tüm bilgiler Güncellensin
if(Match::where('user_id',$user_id)->whereDay('updated_at', '!=' , date('d'))->update($input)){

  return $this->sendResponse($input['shuffle_count']);

}

  // 1 Gün Geçmemişse Son Görülme ve Online Bilgisi Güncellensin
$input = Match::where('user_id',$user_id)->first(); //Match::where('user_id',$user_id)->first();
$input['is_online'] = true;
Match::where('id',$input['id'])->update(['is_online' => $input['is_online']]);

return $this->sendResponse($input['shuffle_count']);

}
catch(\Exception $exception){
  return $this->sendError("Exception",$exception->getMessage());
}

}

/**
* setOfflineInformations() -> Eşleşme Tablosundaki Online Bilgisinii Güncellemek
* @param $user_id
* return -> OK
**/

public function setOnlineInformation($user_id){
   try{
     Match::where('user_id',$user_id)->update(['is_online' => true]);
     return $this->sendResponse("OK");
   }
   catch(\Exception $exception){
     return $this->sendError("Exception",$exception->getMessage());
   }

}




/**
* setOfflineInformations() -> Eşleşme Tablosundaki Online Bilgisini Offline Olarak Güncellemek
* @param $user_id
* return -> OK
**/

public function setOfflineInformation($user_id){
   try{
     Match::where('user_id',$user_id)->update(['is_online' => false]);
     return $this->sendResponse("OK");
   }
   catch(\Exception $exception){
     return $this->sendError("Exception",$exception->getMessage());
   }

}

/**
* updateCounts -> Eşleşme Tablosundaki Shuffle_Count ve Group Bilgilerini Birlikte  +1 Arttırmak İçin Kullanılır
* updateCounts -> Fonksiyon Kullanıcının Kendi Bilgisini Güncellemek İçin Kullanılacak.
* @param $user_id
**/


public function updateCounts($user_id){

try{
  Match::where('user_id',$user_id)->increment('group',1);
  Match::where('user_id',$user_id)->decrement('shuffle_count',1);
  return true;
}

catch(\Exception $exception){
  return false;
}

}

/**
* incrementGroup -> Eşleşme Tablosundaki Group Bilgisini  +1 Arttırmak İçin Kullanılacak
* incrementGroup-> Fonksiyon Karşı Tarafın Bizimle Eşleşmesi Sonucu Bizim Group Bilgimizi Güncellemesi İçin Kullanılacak
* @param $user_id
**/


public function incrementGroup($user_id){

  try{
    Match::where('user_id',$user_id)->increment('group');
    return true;
  }

  catch(\Exception $exception){
    return false;
  }


}


public function findUser($user_id){

try{

if(!(Match::where('user_id',$user_id)->where('shuffle_count','>',0))){
    return $this->sendError("Not Completed","Not Enough Shuffle Count");
}
$count = 0;
$matchs = Match::where('group',0)->limit(1);
foreach($matchs as $match){
/*
if(!(Chat::where('owner_user_id',$user_id)->where('guest_user_id',$match->user_id)->orWhere('owner_user_id',$match->user_id)->where('guest_user_id',$user_id))){
  // kullanıcılar daha önce eşleşmemiş , engel olayı olmamış

  // Event Bilgilerini Güncelleyecek Fonksiyonlar
  if(!($this->incrementGroup($match->user_id))){
    // eşleşilen kullanıcının event bilgisi güncellenemedi
    return $this->sendError('Not Completed','Increment Group Function Not Completed');
  }
  if(!($this->updateCounts($user_id))){
    //bizim event bilgilerimiz Güncellenemedi
    return $this->sendError('Not Completed','UpdateCounts Function Not Completed');
  }

  // TODO : Güncelleme de olacak  : Kullanıcılar Eşleşmiş ve chats tablosundan owner , guest deleted at kontrol edildikten sonra uygunsa engel durumu kontrol edilcek.

  // events bilgileri güncellendi

  // eşleşilen kullanıcı bilgileri gönderilir.

}
*/
$input = User::where('user_id',$match->user_id)->first();
//$input['department_id'] = Department::find($input['department_id'])->value('name');

return $this->sendResponse($input);
}

$this->sendResponse("Users Not Founded");


}
catch(\Exception $exception){
  return $this->sendError("Exception",$exception->getMessage());
}


}





}

 ?>
