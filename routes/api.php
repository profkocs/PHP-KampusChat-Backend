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
        return $request->user();
    });

    // Verify
    Route::post('/verifyEmail','AuthenticationController@verifyEmail');


    // Event
    Route::post('/createEvent','EventController@createEvent');
    Route::put('/updateEvent','EventController@updateEvent');

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
