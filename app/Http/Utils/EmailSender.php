<?php

namespace App\Http\Utils;

use App\Code;
use Illuminate\Support\Facades\Mail;

class EmailSender
{


    public function sendEmail($email, $type)
    {


        $code = $this->random_str(8);
        $to_name = 'User';
        $data = array('name' => "This code is for your process.Please do not share this code with anyone.", 'body' => 'Your Code: ' . $code);

        Mail::send('emails.mail', $data, function ($message) use ($to_name, $email) {
            $message->to($email, $to_name)
                ->subject('KampusChat : Feel Unique');
            $message->from('simpleappvision@gmail.com', 'KampusChat Your Process Code');
        });

        Code::updateOrCreate(['email' => $email], ['code' => $code], ['type' => $type], ['revoked' => false]);


    }


    function random_str(
        int $length = 64,
        string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        if ($length < 1) {
            throw new \RangeException("Length must be a positive integer");
        }
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

}
