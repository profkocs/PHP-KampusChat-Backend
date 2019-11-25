<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Validator;
class RegisterController extends Controller{

  private $client;
  public function __construct(){
  $this->client = Client::find(3);
  }

  /**
  * register() -> Kayıt Fonksiyonu
  * @param Request $request -> İstek Parametreleri İçeren Değişken
  * return $result -> token,user_id
  **/

    public function register(Request $request){


        $validator = Validator::make($request->all(), [
            'department_id' => 'required',
            'email' => 'required|email|max:100|unique:users,email',
            'username' => 'required|max:20|min:2|unique:users,username',
            'password' => 'required|max:10|min:6',
            'fullname' => 'required|max:100|min:2',
            'gender' => 'required|max:1',
            'date_of_birth' => 'required',

        ]);

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['is_verified'] = "no";
        $user = User::create($input);

        $params = [
           'grant_type' => 'password',
           'client_id' => $this->client->id,
           'client_secret' => $this->client->secret,
           'username' => $input['email'],
           'password' => $input['password'],
           'scope' => '*'
        ];


        $request->request->add($params);
        $proxy = Request::create('oauth/token','POST');
        return Route::dispatch($proxy);

    }



}


 ?>
