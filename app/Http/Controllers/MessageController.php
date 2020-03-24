<?php

namespace App\Http\Controllers;

use App\Chat;
use http\Message;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    //


    public function checkNewMessages($user_id, $last_date)
    {


        $messages = new Collection();
        $chats = Chat::where('owner_user_id', $user_id)->orWhere('guest_user_id', $user_id)->get();
        foreach ($chats as $chat) {

            $tmessages = \App\Message::where('chat_id', $chat->id)->where('sender_user_id', '!=', $user_id)->where('is_seen', false)->where('created_at', '>', $last_date)->get();

            foreach ($tmessages as $message) {
                $messages->push($message);
            }


        }

        if (count($messages) > 0) {
            return response()->json($messages, 200);
        }

        return response()->json(["message" => "No Content"], 204);


    }


    public function getAllMessages($chat_id)
    {


        $messages = \App\Message::where('chat_id', $chat_id)->get();

        if (count($messages)) {
            return response()->json($messages, 200);
        }
        return response()->json(["message" => "No Content"], 204);


    }

    public function getNewMessages($chat_id, $user_id, $last_date)
    {

        $messages = \App\Message::where('chat_id', $chat_id)->where('sender_user_id', '!=', $user_id)->where('is_seen', false)->where('created_at', '>', $last_date)->get();


        if (count($messages)) {
            return response()->json($messages, 200);
        }
        return response()->json(["message" => "No Content"], 204);


    }

    public function checkIfUserIsTyping($chat_id, $user_id)
    {

        $chat = Chat::where('id', $chat_id)->first();

        if ($chat->owner_user_id == $user_id) {
            return response()->json(["status" => $chat->is_owner_typing], 200);
        } else {
            return response()->json(["status" => $chat->is_guest_typing], 200);
        }


    }

    public function setUserTypingValue(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'chat_id' => 'required',
            'user_id' => 'required',
            'value' => 'required',
        ]);
        $validator->validate();


        $chat = Chat::where('id', request('chat_id'))->first();

        if ($chat->owner_user_id == request('user_id')) {
            $chat->is_owner_typing = $request->value;
        } else if ($chat->guest_user_id == request('user_id')) {
            $chat->is_guest_typing = $request->value;
        }

        $chat->save();

        return response()->json(["message" => "No Content"], 204);


    }


    public function sendMessage(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'chat_id' => 'required',
            'user_id' => 'required',
            'type' => 'required',
            'message' => 'required'
        ]);
        $validator->validate();


        $input['chat_id'] = request('chat_id');
        $input['sender_user_id'] = request('user_id');
        $input['type'] = request('type');
        $input['message'] = request('message');

        $message = \App\Message::create($input);

        if ($message) {
            return response()->json($message, 200);
        }

        return response()->json(["message" => "Something Went Wrong"], 404);

    }


    public function isMessageSeen($message_id)
    {

        $message = \App\Message::where('id', $message_id)->first();
        return response()->json(["status" => $message->is_seen], 200);


    }


    public function setIsMessageSeenValue(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'message_id' => 'required',
        ]);
        $validator->validate();


        $message = \App\Message::where('id', request('message_id'))->first();
        $message->is_seen = true;
        $message->save();
        return response()->json("OK", 204);


    }


}
