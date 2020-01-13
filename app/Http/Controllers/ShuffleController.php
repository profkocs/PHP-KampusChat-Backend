<?php

namespace App\Http\Controllers;

use App\Chat;
use App\Department;
use App\Event;
use App\User;

class ShuffleController extends Controller
{

    public function shuffle($user_id,$last_user_id)
    {


        $amIAlreadyMatched = Chat::where('guest_user_id', $user_id)->where('is_checked', false)->first();

        if ($amIAlreadyMatched) {

            $user = User::where("id",$amIAlreadyMatched->owner_user_id)->first();
            $department = Department::where("id", $user->department_id)->first();
            $user['department_name'] = $department->name;
            Chat::where('owner_user_id', $amIAlreadyMatched->owner_user_id)->where('guest_user_id', $user_id)->update(['is_checked' => true]);
            return response()->json($user, 200);

        }

        // else

        $events = Event::where("group", "<", 3)->where("group", ">=", 0)->where("user_id", "!=", $user_id)->where("user_id",">",$last_user_id)->get();

        foreach ($events as $event) {
            $is_matched_before = Chat::where('owner_user_id', $user_id)->where('guest_user_id', $event->user_id)->orWhere('owner_user_id', $event->user_id)->where('guest_user_id', $user_id)->first();

            if (!$is_matched_before) {

                $new_user = User::where("id",$event->user_id)->first();
                $department = Department::where("id", $new_user->department_id)->first();
                $new_user['department_name'] = $department->name;

                return response()->json($new_user, 200);

            }

        }

        return response()->json(["message" => "No Content"], 204);


    }

}



