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
        $department = \App\Department::where('id',$user->department_id)->get();
        $user['department_name'] = $department->name();
        return $request->user();
    });

    // Verify
    Route::post('/verifyEmail','AuthenticationController@verifyEmail');

    // Event
   // Route::post('/createEvent','EventController@createEvent');
    Route::put('/updateEvent','EventController@updateEvent');
    Route::put('/setOnline','EventController@setOnline');
    Route::put('/setOffline','EventController@setOffline');


    // Upload Profile Photo and  Edit Profile
    Route::post('/updatePhoto','ProfileController@updateProfilePhoto');
    Route::put('/updateProfile','ProfileController@updateProfile');


    // Shuffle
    Route::get('/shuffle/{user_id}','ShuffleController@shuffle');


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
