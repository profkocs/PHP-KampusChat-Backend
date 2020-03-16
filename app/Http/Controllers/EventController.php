<?php

namespace App\Http\Controllers;

use App\Event;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    //

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /*
        public function createEvent(Request $request)
        {

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
            ]);

            $validator->validate();

            // default degerler
            $input['user_id'] = request('user_id');
            $input['group'] = 0;
            $input['is_online'] = true;

            $event = Event::create($input);

            return response()->json($event, 200);


        }
    */
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    public function updateEvent(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        $validator->validate();

        $user = User::where('email', request('email'))->first();

        $event = Event::where('user_id', $user->id)->first();

        $day = date('d', strtotime($event->last_seen_at));
        // 1 day passed
        if ($day != date('d')) {
            $event->group = 0;
        }
        $event->last_seen_at = Carbon::now();
        $event->is_online = true;
        $event->save();

        return response()->json($event, 200);


    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setOnline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        $validator->validate();

        Event::where('user_id',request('user_id'))->update(['is_online' => true]);
        return response()->json("OK", 204);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setOffline(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        $validator->validate();

        Event::where('user_id',request('user_id'))->update(['is_online' => false]);
        return response()->json("OK", 204);
    }


    public function updateGroup($user_id,$guest_user_id){

        $event = Event::where("user_id",$user_id)->first();
        $event2 = Event::where("user_id",$guest_user_id)->first();


        Event::where("user_id",$user_id)->update(["group" => $event->group+1]);
        Event::where("user_id",$guest_user_id)->update(["group" => $event2->group+1]);

        return response()->json("OK", 204);

    }

  
    public function checkIfUserIsOnline($user_id){

    $event=Event::where("user_id",$user_id)->first();
    return response()->json(["status"=> $event->is_online]);

    }



}
