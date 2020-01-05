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
        $url = request('user_id') . '_image' . time() . '.' . request()->image->getClientOriginalExtension();
        request()->image->storeAs(public_path('images'), $url);

        return response()->json(['url' => $url]);

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
