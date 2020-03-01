<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CampSubscription extends Model
{
    protected $table = 'camp_subscription';
    public $timestamps = false;
    public function user(){
        return $this->belongsTo('\App\User','id','user_id');
    }
}
