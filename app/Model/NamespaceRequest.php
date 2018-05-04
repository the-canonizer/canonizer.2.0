<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class NamespaceRequest extends Model
{
    protected $table = 'namespace_requests';

     public function topic(){
        return $this->belongsTo('\App\Model\Topic','topic_num','topic_num');
    }
    
}
