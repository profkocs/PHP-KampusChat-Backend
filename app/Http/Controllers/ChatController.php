<?php

namespace App\Http\Controllers;

use App\Ban;
use App\Chat;
use App\Like;
use App\User;
use Zend\Diactoros\Request;

class ChatController extends Controller
{


    public function getChats($user_id)
    {

        $input = null;
        $users = array();
        $my_chats = Chat::where('owner_user_id', $user_id)->orWhere('guest_user_id', $user_id)->all();


        foreach ($my_chats as $chat) {

            $other_user_id = ($chat->owner_user_id != $user_id) ? $chat->owner_user_id : $chat->guest_user_id;

            $users[$other_user_id] = User::find($other_user_id)->first();

            $did_user_ban_me = Ban::where('user_id', $other_user_id)->where('banned_user_id', $user_id)->first();
            $did_i_ban_user = Ban::where('user_id', $user_id)->where('banned_user_id', $other_user_id)->first();
            $did_user_like_me = Like::where('user_id', $other_user_id)->where('liked_user_id', $user_id)->first();
            $did_i_liked_user = Like::where('user_id', user_id)->where('liked_user_id', $other_user_id)->first();
            $input = $users[$other_user_id];

            $input['banned_me'] = ($did_user_ban_me) ? true : false;
            $input['banned_user'] = ($did_i_ban_user) ? true : false;
            $input['liked_each_other'] = ($did_i_liked_user && $did_user_like_me) ? true : false;
            $users[$other_user_id] = $input;

        }

        if ($my_chats->count() > 0) {
            return response()->json($input);
        }

        return response()->json("No Content", 204);


    }

    public function addChat(Request $request){


    }



}
