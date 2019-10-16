<?php
namespace App\Http\Controllers;
use App\Match;



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
  $input['last_seen_date'] = date('Y-m-d H:m:s');

 try{
  Match::create($input);
  return $this->sendResponse("OK");
 }

 catch(\Exception $exception){
   return $this->sendError("Exception",$exception->getMessage());
 }


}


/**
* updateInformations() -> Oturum Açıldığında Aktif olan ve Match Tablosu Bilgilerini Güncelleyen Fonksiyon
* updateInformations() -> Ve Güncel Bilgileri Cevap Olarak Dönderen Fonksiyon
* @param $user_id
* return "OK" -> Güncelleme İşlemi Tamamlandı Bilgisi
**/


public function updateInformations($user_id){

// defaults
$input['last_seen_date'] = date('Y-m-d H:m:s');
$input['is_online'] = true;
$input['group'] = 0;
$input['shuffle_count'] = 3;

try{
 // Son Görülmeden İtibaren 1 Gün Geçmişsse tüm bilgiler Güncellensin
if(Match::where('user_id',$user_id)->whereDay('last_seen_date', '!=' , date('d'))->update($input)){

  return $this->sendResponse($input);

}

  // 1 Gün Geçmemişse Son Görülme ve Online Bilgisi Güncellensin ve Tüm Veriler Cevap Olarak Gönderilsin
$input = Match::where('user_id',$user_id)->first();
$input['last_seen_date'] = date('Y-m-d H:m:s');
$input['is_online'] = true;
Match::where('id',$input['id'])->update(['last_seen_date' => $input['last_seen_date'] , 'is_online' => $input['is_online']]);

return $this->sendResponse($input);

}
catch(\Exception $exception){
  return $this->sendError("Exception",$exception->getMessage());
}

}


/**
* setOfflineInformations() -> Eşleşme Tablosundaki Online Bilgisini Offline Olarak Güncellemek
* @param $user_id
* return -> Önemsiz
**/

public function setOfflineInformation($user_id){
   try{
     Match::where('user_id',$user_id)->update(['is_online' => false]);
   }
   catch(\Exception $exception){
     return $this->sendError("Exception",$exception->getMessage());
   }

}

/**
* updateCounts -> Eşleşme Tablosundaki Shuffle_Count ve Group Bilgilerini Birlikte  +1 Arttırmak İçin Kullanılır
* updateCounts -> Fonksiyon Kullanıcının Kendi Bilgisini Güncellemek İçin Kullanılacak.
* @param $user_id
* return "OK" -> İşlem Tamamlandı Bilgisi
**/


public function updateCounts($user_id){

try{
  Match::where('user_id',$user_id)->increment('group',1);
  Match::where('user_id',$user_id)->decrement('shuffle_count',1);
  return $this->sendResponse("OK");
}

catch(\Exception $exception){
  return $this->sendError("Exception",$exception->getMessage());
}

}

/**
* incrementGroup -> Eşleşme Tablosundaki Group Bilgisini  +1 Arttırmak İçin Kullanılacak
* incrementGroup-> Fonksiyon Karşı Tarafın Bizimle Eşleşmesi Sonucu Bizim Group Bilgimizi Güncellemesi İçin Kullanılacak
* @param $user_id
* return "OK" -> İşlem Tamamlandı Bilgisi
**/


public function incrementGroup($user_id){

  try{
    Match::where('user_id',$user_id)->increment('group');
    return $this->sendResponse("OK");
  }

  catch(\Exception $exception){
    return $this->sendError("Exception",$exception->getMessage());
  }


}


/**
* getInformations() -> Eşleşme Tablosundaki Verilere Erişilen Fonksiyon
* @param $user_id
* return "OK" -> İşlem Tamamlandı Bilgisi
* return "Error" -> Kayıt Bulunamadı Hatası
**/

/*
public function getInformations($user_id){

try{
  $data = Match::where('user_id',$user_id)->first(['group','shuffle_count','last_seen_date','is_online']);

if($data){
  return $this->sendResponse($data);
}

  return $this->sendError("Error","Resource Not Found",404);

}
catch(\Exception $exception){
  return $this->sendError("Exception",$exception->getMessage());
}


}
*/




}

 ?>
