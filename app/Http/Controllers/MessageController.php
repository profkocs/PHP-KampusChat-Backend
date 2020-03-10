<?php

namespace App\Http\Controllers;

use App\Chat;
use http\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MessageController extends Controller
{
    //


    public function checkNewMessages($user_id)
    {

        $messages = new Collection();
        $chats = Chat::where('owner_user_id', $user_id)->orWhere('guest_user_id', $user_id)->get();

        foreach ($chats as $chat) {

            $message = \App\Message::where('chat_id', $chat->id)->where('is_seen', false)->last();
            if ($message) {
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

    public function getNewMessages($chat_id, $user_id)
    {

        $messages = \App\Message::where('chat_id', $chat_id)->where('sender_user_id', '!=', $user_id)->where('is_seen', false)->get();

        if (count($messages)) {
            return response()->json($messages, 200);
        }
        return response()->json(["message" => "No Content"], 204);


    }

    public function checkIfUserIsTyping($chat_id, $user_id)
    {

        $chat = Chat::where('id', $chat_id)->first();

        if ($chat->owner_user_id != $user_id) {
            return response()->json($chat->is_owner_typing, 200);
        } else {
            return response()->json($chat->is_guest_typing, 200);
        }


    }

    public function setUserTypingValue($chat_id, $user_id, $value)
    {

        $chat = Chat::where('id', $chat_id)->first();

        if ($chat->owner_user_id == $user_id) {
            $chat->is_owner_typing = $value;
        } else if ($chat_id->guest_user_id == $user_id) {
            $chat->is_guest_typing = $value;
        }

        $chat->save();

        return response()->json(["message" => "No Content"], 204);


    }


    public function sendMessage($chat_id, $user_id, $type, $message)
    {

        $input['chat_id'] = $chat_id;
        $input['sender_user_id'] = $user_id;
        $input['type'] = $type;
        $input['message'] = $message;

        $message = \App\Message::create($input);


        if ($message) {
            return response()->json($message, 200);
        }

        return response()->json(["message" => "Something Went Wrong"], 404);

    }


    public function isMessageSeen($message_id)
    {

        $message = \App\Message::where('id', $message_id)->first();
        return response()->json($message->is_seen, 200);


    }


    public function setIsMessageSeenValue($message_id){

        $message = \App\Message::where('id', $message_id)->first();
        $message->is_seen = true;
        return response()->json("OK", 204);


    }


}
