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

        return  env('SHORT_CODE_BASE_PATH').$this->file_name;
        $pos = strrpos( $this->file_name, ".");
        if ($pos === false)
        return false;
        $ext = strtolower(trim(substr( $this->file_name, $pos)));
        $imgExts = array(".gif", ".jpg", ".jpeg", ".png",".PNG", ".tiff", ".tif"); // this is far from complete but that's always going to be the case...
        if ( in_array($ext, $imgExts) )
        {
            return '[[file:'.url('/files/'.$this->file_name).']]';
        }else{
            return '[[file:'.url('/files/'.$this->file_name).']]';
        }
        
    }
	
}
