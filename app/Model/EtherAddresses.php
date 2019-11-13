<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class EtherAddresses extends Model
{
    protected $table = 'ether_address';
    public $timestamps = false;
     public function user(){
        return $this->belongsTo('\App\User','id','user_id');
    }
}
