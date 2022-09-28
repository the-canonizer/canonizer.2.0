<?php

namespace App;

use App\Facades\Util;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Authenticatable implements CanResetPasswordContract
{
    use Notifiable;
    use CanResetPassword;
    protected $table = 'person';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name','middle_name', 'email', 'password','language','status','otp','provider','provider_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    
    public static function getByEmail($email){        
        $user = User::where('email', $email)->first();
       return !empty($user) ? $user : false;
    }

    public function getNameAttribute(){
       return ucwords ($this->first_name.' '.$this->last_name);
    }

    /**
     * Get user by user id
     * @param interger $id
     * @return User 
     */
    public static function getById($id) {
        return User::where('id', $id)->first();
    }

    public static function ownerCode($userID){
        return $ownerCode = Util::canon_encode($userID);
    }
}
