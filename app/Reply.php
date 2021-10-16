<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $table = 'post';
    protected $guarded = [];
    public $timestamps = false; /** By Reena Nalwa Talentelgia */

     /**
     * By Reena Nalwa
     * Talentelgia #780
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            $model->created_at = time();
            $model->updated_at = time();
        });

        self::created(function($model){
            // ... code here
        });

        self::updating(function($model){
            $model->updated_at = time();
        });

        self::updated(function($model){
            // ... code here
        });

        self::deleting(function($model){
            $model->updated_at = time();
        });

        self::deleted(function($model){
            // ... code here
        });
    }

    // Fillable Columns

    protected $fillable = ['c_thread_id', 'user_id', 'body'];

    public function owner()
    {
        return $this->belongsTo('App\Model\Nickname'::class, 'user_id');
    }
}
