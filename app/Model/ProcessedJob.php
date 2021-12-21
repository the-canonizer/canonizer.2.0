<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class ProcessedJob extends Model
{
    // Table name
    protected $table = "processed_jobs";
    protected $dates = ['created_at', 'updated_at'];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payload', 'status', 'code', 'response', 'created_at', 'updated_at'
    ];

}