<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    //

    public function updateProfilePhoto()
    {
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id' => 'required'
        ]);

        request()->image->move(public_path('images'), request('user_id'));

        return response()->json(['url' => public_path('images') . '/' . request('user_id')]);

    }


    public function updateProfile()
    {
        request()->validate([
            'bio' => 'required',
            'url' => 'required',
            'user_id' => 'required'
        ]);


        User::where('user_id', request('user_id'))->update(['bio' => request('bio'), 'profile_photo_url' => request('url')]);
        return response()->json("OK", 204);

    }


}
