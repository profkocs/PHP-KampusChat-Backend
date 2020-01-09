<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{


    public function updateProfile()
    {
        request()->validate([
            'bio' => 'required',
            'url' => 'required',
            'user_id' => 'required'
        ]);


        User::where('id', request('user_id'))->update(['bio' => request('bio'), 'profile_photo_url' => request('url')]);
        return response()->json("OK", 204);

    }


}
