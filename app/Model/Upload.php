<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Library\General;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use DB;

class Upload extends Model
{
    protected $table = 'uploads';
    
    public function getShortCode(){
        $pos = strrpos( $this->file_id, ".");
        if ($pos === false)
        return false;
        $ext = strtolower(trim(substr( $this->file_id, $pos)));
        $imgExts = array(".gif", ".jpg", ".jpeg", ".png", ".tiff", ".tif"); // this is far from complete but that's always going to be the case...
        if ( in_array($ext, $imgExts) )
        {
            return '[[img:'.url('/storage/uploads/'.$this->file_id).']]';
        }else{
            return '[[file:'.url('/storage/uploads/'.$this->file_id).']]';
        }
        
    }
	
}
