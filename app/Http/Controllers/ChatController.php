<?php

namespace App\Http\Controllers;

use App\Ban;
use App\Chat;
use App\Department;
use App\Like;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ChatController extends Controller
{


    public function getChats($user_id)
    {

        $input = null;
        $users = array();
        $my_chats = Chat::where('owner_user_id', $user_id)->orWhere('guest_user_id', $user_id)->get();


        foreach ($my_chats as $chat) {

            $other_user_id = ($chat->owner_user_id != $user_id) ? $chat->owner_user_id : $chat->guest_user_id;

            $users[$other_user_id] = User::find($other_user_id)->get();

            $did_user_ban_me = Ban::where('user_id', $other_user_id)->where('banned_user_id', $user_id)->get();
            $did_i_ban_user = Ban::where('user_id', $user_id)->where('banned_user_id', $other_user_id)->get();
            $did_user_like_me = Like::where('user_id', $other_user_id)->where('liked_user_id', $user_id)->get();
            $did_i_liked_user = Like::where('user_id', $user_id)->where('liked_user_id', $other_user_id)->get();
            $input = $users[$other_user_id];

            $input['did_user_banned_me'] = ($did_user_ban_me) ? true : false;
            $input['did_i_banned_user'] = ($did_i_ban_user) ? true : false;
            $input['liked_each_other'] = ($did_i_liked_user && $did_user_like_me) ? true : false;

            $department = Department::where('id',$input['id'])->get();
            $input['department_name'] = $department->name;
            $users[$other_user_id] = $input;

        }

        if (count($my_chats) > 0) {
            return response()->json($input);
        }

        return response()->json(["message" => "No Content"], 204);


    }

    public function addChat(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'owner_user_id' => 'required',
            'guest_user_id' => 'required',
        ]);

        $validator->validate();

        $input = $request->all();
        Chat::create($input);

        return response()->json("OK",204);
    }


}
