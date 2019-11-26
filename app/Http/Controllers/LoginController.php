<?php

use App\Http\Controllers\Controller;


use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client;


class LoginController extends Controller {



    private $client;
    public function __construct(){
        $this->client = Client::find(3);
    }

    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'username' => 'required',
        ]);

        $input = $request->all();

        $params = [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => $input['username'],
            'password' => $input['password'],
            'scope' => '*'
        ];


        $request->request->add($params);
        $proxy = Request::create('oauth/token','POST');
        return Route::dispatch($proxy);



    }

    public function logout(Request $request){
        $accessToken = Auth::user()->token();
        DB::table('oaunt_refresh_tokens')->where('access_token_id',$accessToken->id)->update(['revoked'=>true]);
        $accessToken->revoke();
        return response()->json("OK",204);
    }

    public function forgotPassword(Request $request){
// email kodu gönder
    }


    public function verifyCode(Request $request){
        // kodu doğrula
    }



}



?>
