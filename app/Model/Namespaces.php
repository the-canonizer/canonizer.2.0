<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Namespaces extends Model {

    protected $table = 'namespace';
    public $timestamps = false;

    public $fillable = ['name','parent_id','label'];
	
    public function parentNamespace(){
        return $this->belongsTo('\App\Model\Namespaces','parent_id');
    }
    
}
