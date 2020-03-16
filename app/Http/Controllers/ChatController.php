<?php

namespace App\Http\Controllers;

use App\Ban;
use App\Chat;
use App\Department;
use App\Like;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;


class ChatController extends Controller
{


    public function getChats($user_id)
    {


        $users = new Collection();
        $my_chats = Chat::where('owner_user_id', $user_id)->orWhere('guest_user_id', $user_id)->get();


        foreach ($my_chats as $chat) {

            $other_user_id = ($chat->owner_user_id != $user_id) ? $chat->owner_user_id : $chat->guest_user_id;

            $user = User::where("id",$other_user_id)->first();

            $did_user_ban_me = Ban::where('user_id', $other_user_id)->where('banned_user_id', $user_id)->first();
            $did_i_ban_user = Ban::where('user_id', $user_id)->where('banned_user_id', $other_user_id)->first();
            $did_user_like_me = Like::where('user_id', $other_user_id)->where('liked_user_id', $user_id)->first();
            $did_i_liked_user = Like::where('user_id', $user_id)->where('liked_user_id', $other_user_id)->first();

            $chat['did_user_banned_me'] = ($did_user_ban_me) ? true : false;
            $chat['did_i_banned_user'] = ($did_i_ban_user) ? true : false;
            $chat['liked_each_other'] = ($did_i_liked_user && $did_user_like_me) ? true : false;

            $department = Department::where('id',$user->department_id)->first();
            $chat['department_name'] = $department->name;
            $chat['profile_photo_url'] = $user->profile_photo_url;
            //$users[$other_user_id] = $input;
            //$users->push($user);

        }

        if (count($my_chats) > 0) {
            return response()->json($my_chats);
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
