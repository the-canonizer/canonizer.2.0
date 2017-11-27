<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Camp extends Model
{
    protected $table = 'camp';
    
    
    public function childcamps(){
        return ;
    }
}
