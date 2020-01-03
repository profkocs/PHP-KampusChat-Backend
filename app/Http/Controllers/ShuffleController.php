<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Event;
use App\User;

class ShuffleController extends Controller
{

    public function shuffle($user_id)
    {

        $count = 0;
        $last_user_id = -1;
        while ($count < 1000) {
            $event = Event::where("group", "=", 0)->where("user_id", ">", $last_user_id)->first();

            if ($event) {

                if($user_id != $event->user_id){
                    $last_user_id = $event->user_id;

                    $is_matched_before = Chat::where('owner_user_id', $user_id)->where('guest_user_id', $last_user_id)->orWhere('owner_user_id', $last_user_id)->where('guest_user_id', $user_id)->first();

                    if (!$is_matched_before) {

                        $user = User::find($last_user_id)->first();

                        return response()->json($user, 200);


                    }
                }
            } else {
                return response()->json(["message" => "No Content"], 200);
            }

            $count++;
        }

        return response()->json(["message" => "No Content"], 200);


    }

}



