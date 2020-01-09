<?php

namespace App\Http\Controllers;

use App\Ban;
use App\Department;
use App\Like;
use App\User;
use http\Env\Response;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ListsController extends Controller
{


    public function likeUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'liked_user_id' => 'required',


        ]);

        $validator->validate();

        $input = $request->all();

        Like::create($input);
        $did_user_like_me = Like::where('user_id', request('liked_user_id'))->where('liked_user_id', request('user_id'))->first();

        if ($did_user_like_me) {
            return response()->json("OK", 200);
        }

        return response()->json("OK", 204);


    }

    public function getLikedUsers($user_id)
    {

        $users = new Collection();
        $my_likes = Like::where('user_id', $user_id)->get();

        foreach ($my_likes as $like) {

            $did_user_like_me = Like::where('user_id', $like->liked_user_id)->where('liked_user_id', $user_id)->first();

            if ($did_user_like_me) {

                $did_user_ban_me_or_i_did = Ban::where('user_id', $like->liked_user_id)->where('banned_user_id', $user_id)->orWhere('user_id', $user_id)->where('banned_user_id', $like->liked_user_id)->first();

                if (!$did_user_ban_me_or_i_did) {

                    $user = User::where("id",$like->liked_user_id)->first();
                    return response()->json($user, 200);
                    $department = Department::where('id', $user->department_id)->first();
                    $user['department_name'] = $department->name;
                    // $users[$like->liked_user_id] = $user;
                    $users->push($user);

                }


            }

        }

        if (count($users) > 0) {

            return response()->json($users, 200);
        }

        return response()->json(["message" => "No Content"], 204);

    }


    public function getBannedUsers($user_id)
    {

        $users = new Collection();
        $my_bans = Ban::where('user_id', $user_id)->get();
        foreach ($my_bans as $ban) {
            $user = User::find($ban->banned_user_id)->first();
            $department = Department::where('id', $user->department_id)->first();
            $user['department_name'] = $department->name;
            // $users[$ban->banned_user_id] = $user;
            $users->push($user);
        }

        if (count($users) > 0) {

            return response()->json($users, 200);
        }

        return response()->json(["message" => "No Content"], 204);


    }

    public function banUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'banned_user_id' => 'required',

        ]);

        $validator->validate();

        $input = $request->all();

        Ban::create($input);
        return response()->json("OK", 204);

    }

    public function removeBan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'banned_user_id' => 'required',

        ]);
        $validator->validate();

        Ban::where('user_id', request('user_id'))->where('banned_user_id', request('banned_user_id'))->delete();
        return response()->json("OK", 204);

    }


}
