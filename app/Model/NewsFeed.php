<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class NewsFeed extends Model
{
    protected $table = 'news_feed';
    public $timestamps = false;
    
    public static function boot() {
        static::created(function ($model) {
            if ($model->camp_num != '' && $model->topic_num != '') {
                $nextOrder = DB::table('news_feed')->where('topic_num', '=', $model->topic_num)->where('camp_num','=',$model->camp_num)->max('order_id');
                $nextOrder = $nextOrder+1;
                $model->order_id = $nextOrder;
                $model->update();
            }
        });
    }
}
