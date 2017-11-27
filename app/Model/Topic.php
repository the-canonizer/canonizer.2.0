<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    
    protected $table = 'topic';
    public $timestamps = false;
            
            
    public function camps(){
        return $this->hasMany('App\Model\Camp','topic_num','topic_num');
    }
    
}
