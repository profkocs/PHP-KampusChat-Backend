<?php
namespace App\Http\Controllers;

use App\Event;
use App\Http\Utils\EmailSender;
use App\User;
use App\Code;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client;

class AuthenticationController extends Controller
{


    private $client;

    public function __construct()
    {
        $this->client = Client::find(1);
    }

    /**
     * @param Request $request
     * @return mixed
     *
     */

    public function register(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'department_id' => 'required',
            'email' => 'required|email|max:100|unique:users,email',
            'username' => 'required|max:20|min:2|unique:users,username',
            'password' => 'required|max:10|min:6',
            'fullname' => 'required|max:100|min:2',
            'gender' => 'required|max:1',
            'date_of_birth' => 'required',

        ]);

        $validator->validate();

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user_id = User::create($input)->id;

        $email_sender = new EmailSender();
        $email_sender->sendEmail(request('email'), 'verification');

        Event::create(["user_id" => $user_id,"group" => 0 , "is_online" => false]);

        $params = [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => request('email'),
            'password' => request('password'),
            'scope' => '*'
        ];

        $request->request->add($params);
        $proxy = Request::create('oauth/token', 'POST');

        return \Illuminate\Support\Facades\Route::dispatch($proxy);
    }

    /**
     * @param Request $request
     * @return mixed
     *
     */

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'username' => 'required',
        ]);
        $validator->validate();

        $params = [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => request('username'),
            'password' => request('password'),
            'scope' => '*'
        ];


        $request->request->add($params);
        $proxy = Request::create('oauth/token', 'POST');

        return Route::dispatch($proxy);


    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function logout(Request $request)
    {
        $accessToken = Auth::user()->token();
        DB::table('oauth_refresh_tokens')->where('access_token_id', $accessToken->id)->update(['revoked' => true]);
        $accessToken->revoke();
        return response()->json("OK", 204);
    }

    /**
     * @param $email
     * @return \Illuminate\Http\JsonResponse
     *
     */

    public function forgotPassword($email)
    {


            if(User::where('email',$email)->firstOrFail()){
                $email_sender = new EmailSender();
                $email_sender->sendEmail($email, "reset_password");
                return response()->json("OK", 204);
            }


    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);
        $validator->validate();

        $code = Code::where('email', request('email'))->where('code', request('code'))->where('type', 'reset_password')->whereDay('updated_at', '=', date('d'))->where('revoked', false)->firstOrFail();
        if ($code) {
            $code->revoked = true;
            $code->save();
            User::where('email', request('email'))->update(['password' => bcrypt(request('password'))]);
            return response()->json("OK", 204);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function verifyEmail(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'email' => 'required',
        ]);
        $validator->validate();

        $code = Code::where('email', request('email'))->where('code', request('code'))->where('type', "verification")->where('revoked', false)->firstOrFail();
        if ($code) {
            $code->revoked = true;
            $code->save();
            User::where('email', request('email'))->update(['email_verified_at' => Carbon::now()]);
            return response()->json("OK", 204);
        }

    }


}

?>
