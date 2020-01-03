<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Validator;
use Zend\Diactoros\Request;

class ListsController extends Controller
{



    public function likeUser(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'liked_user_id' => 'required',


        ]);

        $validator->validate();

        $input = $request->all();

        Like::create($input);


    }

    public function getLikedUsers($user_id)
    {

        $users = array();
        $my_likes = Like::where('user_id', $user_id)->all();
        foreach ($my_likes as $like) {

            $did_user_like_me = Like::where('user_id', $like->liked_user_id)->where('liked_user_id', $user_id)->first();

            if ($did_user_like_me) {

                $did_user_ban_me_or_i_did = Ban::where('user_id', $like->liked_user_id)->where('banned_user_id', $user_id)->orWhere('user_id', $user_id)->where('banned_user_id', $like->liked_user_id)->first();

                if (!$did_user_ban_me_or_i_did) {

                    $users[$like->liked_user_id] = User::find($like->liked_user_id)->first();

                }


            }

        }

        if ($users->count() > 0) {

            return response()->json($users, 200);
        }

        return response()->json("No Content", 200);

    }


    public function getBannedUsers($user_id){

        $users = array();
        $my_bans = Ban::where('user_id',$user_id)->first();
        foreach ($my_bans as $ban){


            $users[$ban->banned_user_id] = User::find($ban->banned_user_id)->first();

        }

        if ($users->count() > 0) {

            return response()->json($users, 200);
        }

        return response()->json("No Content", 200);



    }

    public function banUser(Request $request){

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'banned_user_id' => 'required',

        ]);

        $validator->validate();

        $input = $request->all();

        Ban::create($input);


    }






}
