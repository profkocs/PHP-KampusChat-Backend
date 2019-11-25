<?php
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client;


class LoginController extends Controller {


private $client;
public function __construct(){
  $this->client = Client::find(3);
}

public function login(){

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



}



?>
