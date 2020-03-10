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
Route::group(['middleware' => ['auth:api']], function () {

    //Profile
    Route::get('/user', function (Request $request) {

        $user = $request->user();
        $department = \App\Department::where('id',$user->department_id)->first();
        $user['department_name'] = $department->name;
        return $user;
    });

    // Verify
    Route::post('/verifyEmail','AuthenticationController@verifyEmail');

    // Event
   // Route::post('/createEvent','EventController@createEvent');
    Route::put('/updateEvent','EventController@updateEvent');
    Route::put('/setOnline','EventController@setOnline');
    Route::put('/setOffline','EventController@setOffline');
    Route::put('/updateGroup','EventController@updateGroup');


    // Upload Profile Photo and  Edit Profile
    Route::post('/updatePhoto','ProfileController@updateProfilePhoto');
    Route::put('/updateProfile','ProfileController@updateProfile');


    // Shuffle
    Route::get('/shuffle/{user_id}/{count}','ShuffleController@shuffle');
//

    // Like
    Route::post('/likeUser','ListsController@likeUser');
    Route::get('/getLikedUsers/{user_id}','ListsController@getLikedUsers');

    // Ban and Remove Ban

    Route::post('/banUser','ListsController@banUser');
    Route::get('/getBannedUsers/{user_id}','ListsController@getBannedUsers');
    Route::post('/removeBan','ListsController@removeBan');


    // Chat
    Route::post('/addChat','ChatController@addChat');
    Route::get('/getChats/{user_id}','ChatController@getChats');


    // Message
    Route::get('/checkNewMessages/{user_id}','MessageController@checkNewMessages');
    Route::get('/getAllMessages/{chat_id}','MessageController@getAllMessages');
    Route::get('/getNewMessages/{chat_id}/{user_id}','MessageController@getNewMessages');
    Route::get('/checkUserTyping/{chat_id}/{user_id}','MessageController@checkIfUserIsTyping');
    Route::put('/setUserTypingValue/{chat_id}/{user_id}/{value}','MessageController@setUserTypingValue');
    Route::post('/sendMessage/{chat_id}/{user_id}/{type}/{message}','MessageController@sendMessage');
    Route::get('/isMessageSeen/{message_id}','MessageController@isMessageSeen');
    Route::put('/setIsMessageSeenValue/{message_id}','MessageController@setIsMessageSeenValue');

    // Logout
    Route::post('/logout', 'AuthenticationController@logout');
});

// Education
Route::get('/universities', 'EducationController@universities');
Route::get('/departments/{id}', 'EducationController@departments');

//Authentication
Route::post('/register', 'AuthenticationController@register');
Route::post('/login', 'AuthenticationController@login');
Route::get('/forgotPassword/{email}','AuthenticationController@forgotPassword');
Route::post('/updatePassword','AuthenticationController@updatePassword');
