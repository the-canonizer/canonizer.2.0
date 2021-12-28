<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

/*
|=================================================================
| @Class        :   Util
| @Description  :   Return the isnatnce of the object mapped to the service container
| @Author       :   Ashish Upadhyay
| @Created_at   :   14-Dec-2021
| @Modified_at  :   
| @Version      :   1.0
|=================================================================
*/

class Util extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'util';
    }
}