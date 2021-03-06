<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $fillable = [
        'id','fullname', 'email', 'password','department_id','username','gender','date_of_birth','email_verified_at'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function findForPassport($username)
    {

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            return $this->where('email', $username)->first();
        }
        return $this->where('username', $username)->first();
    }


}
