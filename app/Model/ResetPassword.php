<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    //

    protected $primaryKey = 'id';

    protected $fillable = [
        'id', 'user_id', 'reset_at', 'expires_at'
    ];
}
