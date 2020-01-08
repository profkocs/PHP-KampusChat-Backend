<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Department;
use App\Event;
use App\User;

class ShuffleController extends Controller
{

    public function shuffle($user_id)
    {


        $amIAlreadyMatched = Chat::where('guest_user_id', $user_id)->where('is_checked', false)->first();

        if ($amIAlreadyMatched) {

            $user = User::find($amIAlreadyMatched->owner_user_id)->first();
            $department = Department::where("id", $user->department_id)->first();
            $user['department_name'] = $department->name;
            Chat::where('owner_user_id', $amIAlreadyMatched->owner_user_id)->where('guest_user_id', $user_id)->update(['is_checked' => true]);
            return response()->json($user, 200);

        }

        // else
        $count = 0;
        $last_user_id = -1;

        $event = Event::where("group", "<", 3)->where("group", ">=", 0)->where("user_id", ">", $last_user_id)->where("user_id", "!=", $user_id)->first();


        return response()->json($event, 200);


        /*
        while ($count < 1000) {


            if ($event) {
                $last_user_id = $event->user_id;
                $is_matched_before = Chat::where('owner_user_id', $user_id)->where('guest_user_id', $last_user_id)->orWhere('owner_user_id', $last_user_id)->where('guest_user_id', $user_id)->first();

                if (!$is_matched_before) {

                    $user = User::find($event->user_id)->first();
                    $department = Department::where("id", $user->department_id)->first();
                    $user['department_name'] = $department->name;

                    return response()->json($user, 200);


                }

            } else {
                return response()->json(["message" => "No Content"], 204);
            }

            $count++;
        }

        return response()->json(["message" => "No Content"], 204);

*/
    }

}



