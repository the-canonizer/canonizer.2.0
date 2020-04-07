<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SocialUser extends Model
{
   protected $table = 'social_users';
    public $timestamps = false;
     protected $fillable = [
         'social_email', 'user_id','provider','provider_id'
    ];
    public function user(){
        return $this->belongsTo('\App\User','id','user_id');
    }
}
