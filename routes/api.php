<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::group(['middleware' => 'auth:api'], function() {
// lots of routes that require auth middleware
Route::get('/user', function (Request $request) {return $request->user();});

});

Route::get('/universities','EducationController@universities');
Route::get('/departments/{id}','EducationController@departments');

Route::post('/register','AuthenticationController@register');
Route::post('/login','AuthenticationController@login');
Route::get('/isEmail/{email}','AuthenticationController@isEmailUsed');
Route::get('/isUsername/{username}','AuthenticationController@isUsernameUsed');
Route::get('/code/{email}','AuthenticationController@sendCode');
Route::post('/code','AuthenticationController@verifyCode');
Route::post('/password','AuthenticationController@updatePassword');
Route::get('/verify/{email}','AuthenticationController@verifyUser');
//Route::get('/checkBannedList/{id}','AuthenticationController@checkBannedList');




Route::get('/createEvent/{user_id}','MatchController@createInformations');
Route::put('/updateEvent/{user_id}','MatchController@updateInformations');
Route::put('/setOffline/{user_id}','MatchController@setOfflineInformation');
Route::put('/setOnline/{user_id}','MatchController@setOnlineInformation');
Route::get('/getMatch/{user_id}','MatchController@getInformations');


Route:get('/shuffle/{user_id}','MatchController@findUser');
