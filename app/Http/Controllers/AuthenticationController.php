<?php
namespace App\Http\Controllers;
use App\User;
use App\Code;
use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

/**
* Created by Burak on 09/10/2019
* Bu Sınıf Oturum Almak İçin Gereken İşlemleri İçeriyor
**/


class AuthenticationController extends BaseController{

/**
* register() -> Kayıt Fonksiyonu
* @param Request $request -> İstek Parametreleri İçeren Değişken
* return $result -> token,user_id
**/

  public function register(Request $request){


      // Requestin İçerdiği Değişkenleri Kontrol Ediyoruz.
      $validator = Validator::make($request->all(), [
          'department_id' => 'required',
          'email' => 'required|email|max:100',
          'username' => 'required|max:20|min:2',
          'password' => 'required|max:10|min:6',
          'fullname' => 'required|max:50|min:2',
          'gender' => 'required|max:1',
          'date_of_birth' => 'required',

      ]);

      $input = $request->all();
      $input['is_verified'] = "no";

      if ($validator->fails()) {
          return $this->sendError("Validation",$validator->errors());
      }

      // email daha önce kullanılmış mı
      if(($this->isEmailAvailable($input['email'])) == "not"){
        return $this->sendError("Not Completed","Email Already Used");
      }else if(($this->isEmailAvailable($input['email'])) == "exception"){
        return $this->sendError("Exception","Something Went Wrong");
      }

      //username daha önce kullanılmışmı
      if(($this->isUsernameAvailable($input['username'])) == "not"){
        return $this->sendError("Not Completed","Username Already Used");
      }else if(($this->isUsernameAvailable($input['username'])) == "exception"){
        return $this->sendError("Exception","Something Went Wrong");
      }

      //şifreleme yapıyoruz
      $input['password'] = bcrypt($input['password']);

     try{

      if($this->sendCode($input['email'])){
        // kodu gönderdikten sonra kullanıcıyı kayıt ediyoruz.
        $user = User::create($input);
        $result['token'] = $user->createToken('KampusChat')-> accessToken;
        $result['user_id'] =  $user->id;

      }else{
      // kod göndermede hata oluştu
      return $this->sendError("Exception","Code Is Not Sended");

      }

      // işlem başarılı sonuç dönder
      return $this->sendResponse($result);

     }catch(\Exception $exception){
       return $this->sendError("Exception",$exception->getMessage());
     }

  }




  /**
  * isEmailAvailable -> Email Adresinin Sistemde Olup Olmadığını Kontrol Eder
  * @param $email -> Aranacak Email Adresi
  **/

  public function isEmailAvailable($email){


    try{

     if(User::where('email',$email)->value('email')){
       return "not";
     }
     else{
       return "available";
     }

    }
    catch(\Exception $exception){
          return "exception";
    }


  }

  /**
  * isUsernameAvailable() -> Kullanıcı İsminin Sistemde Olup Olmadığını Kontrol Eder
  * @param $username -> Aranacak Kullanıcı ismi
  **/


  public function isUsernameAvailable($username){

    try{

     if(User::where('username',$username)->value('username')){
       return "not";

     }
     else{
       return "available";
     }

    }
    catch(\Exception $exception){
          return "exception";
    }



  }

  /**
  * login() -> Oturum Açma Fonksiyonu
  * @param Request $request -> İstek Parametreleri İçeren Değişken
  * return $result -> token,user_id
  **/

  public function login(Request $request){
    // Varsayılan Giriş Sütünu
    $login_column = 'username';

    // username ile mi giriş yapılacak yoksa email ile mi kontrol ediyoruz.
    $validator = Validator::make($request->all(), [
        'username' => 'required|max:20',
        'password' => 'required|max:10'
    ]);

    if ($validator->fails()) {

       $validator_email = Validator::make($request->all(),[
         'email'    => 'required|max:100',
         'password' => 'required|max:10'
       ]);

       if($validator_email->fails()){
         return $this->sendError("Validation",$validator->errors());
       }
      // email ile giriş yapılacagına karar verdik.
      $login_column = 'email';

    }

    $input = $request->all();

    try {

          if(Auth::attempt([$login_column => $input[$login_column], 'password' => $input['password']])){

            $user = Auth::user();
            $result['token'] = $user->createToken('KampusChat')-> accessToken;
            $result['user_id'] = $user->getAuthIdentifier();
            return $this->sendResponse($result);

          }
          else{
            return $this->sendError("Unauthorized","Informations May Not Be Correct",401);
          }


    }
    catch(\Exception $exception){
          return $this->sendError("Exception",$exception->getMessage());
    }


  }


  /**
  * sendCode() -> Email Adresine Tek Kullanımlık Kod Gönderecek Fonksiyon
  * @param $email -> Kod Gönderilecek ve Sistemde Kayıtlı Olan Email Adresi
  **/

  public function sendCode($email){

    try{

    $code = str_random(6);

    $to_name = 'User';
    $data = array('name'=>"This code is for your verification.Please do not share this code with anyone.", 'body' => 'Your Verification Code: '.$code);
    Mail::send('emails.mail', $data, function($message) use ($to_name,$email) {
    $message->to($email, $to_name)
    ->subject('KampusChat : Feel Unique');
    $message->from('simpleappvision@gmail.com','KampusChat Verification Code');
    });

    Code::updateOrCreate(['email' => $email], ['code' => $code]);
    return true;
  }
  catch(\Exception $exception){
      return false;
  }

  }


/**
* verifyCode() -> Email Adresine Gönderilen Tek Kullanımlık Kodu Onaylayacak Fonksiyon
* @param Request $request -> Email Adresi ve Kodu İçeren Değişken
**/

  public function verifyCode(Request $request){

    // Requestin İçerdiği Değişkenleri Kontrol Ediyoruz.
    $validator = Validator::make($request->all(), [
        'code' => 'required|max:6',
        'email' => 'required|email|max:200',
    ]);

    if ($validator->fails()) {
        return $this->sendError("Validation",$validator->errors());
    }

  try{

  $input = $request->all();

  if(Code::where('email',$input['email'])->where('code',$input['code'])->whereDay('updated_at', '=' , date('d'))->value('id')){

    if(User::where('email',$input['email'])->update(['is_verified' => 'yes'])){
      return $this->sendResponse("OK");
    }
      return $this->sendError("Exception","Something Went Wrong");
  }

    return $this->sendError("Not Completed","Code is Incorrect");


  }catch(\Exception $exception){
        return $this->sendError("Exception",$exception->getMessage());
  }

  }


  /**
  * updatePassword() -> Kullanıcı Şifresini Yenileyecek Olan Fonksiyon
  * @param Request $request -> Email Adresi ve Password İçeren Değişken
  **/

  public function updatePassword(Request $request){

    // Requestin İçerdiği Değişkenleri Kontrol Ediyoruz.
    $validator = Validator::make($request->all(), [
        'password' => 'required|max:10|min:6',
        'email' => 'required|email|max:200',
    ]);

    if ($validator->fails()) {
        return $this->sendError("Validation",$validator->errors());
    }


     try{

         $input = $request->all();
         if(User::where('email',$input['email'])->update(['password' => $input['password']])){
           return $this->sendResponse('OK');
         }
         return $this->sendError("Not Completed","Resource Not Found");

     }
     catch(\Exception $exception){
           return $this->sendError("Exception",$exception->getMessage());
     }


  }


  }

?>
