<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    //

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function createEvent(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        $validator->validate();

        // default degerler
        $input['user_id'] = request('user_id');
        $input['group'] = 0;
        $input['shuffle_count'] = 3;
        $input['is_online'] = true;

        $event = Event::create($input);

        return response()->json($event, 200);


    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */


    public function updateEvent(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        $validator->validate();

        $event = Event::where('user_id', request('user_id'))->first();

        // 1 day passed
        if (date_format($event->updated_at, 'd') != date('d')) {
            $event->group = 0;
            $event->shuffle_count = 3;
        }
        $event->is_online = true;
        $event->save();

        return response()->json($event, 200);


    }


}
