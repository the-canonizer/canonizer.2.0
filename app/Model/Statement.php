<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Statement extends Model {

    protected $table = 'statement';
    public $timestamps = false;

    protected static $tempArray = [];

    const AGREEMENT_CAMP = "Agreement";

    public static function boot() {
        
    }   

}
