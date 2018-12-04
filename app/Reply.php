<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $table = 'post';
    protected $guarded = [];
    // Fillable Columns

    protected $fillable = ['c_thread_id', 'user_id', 'body'];

    public function owner()
    {
        return $this->belongsTo('App\Model\Nickname'::class, 'user_id');
    }

    /**
     * [votes description]
     * @return [type] [description]
     */
    public function votes()
    {
        return $this->morphMany(Vote::class, 'vote');
    }

    /**
     * [vote description]
     * @return [type] [description]
     */
    public function vote()
    {
        $attr = ['user_id' => auth()->id(),
                 'post_id' => $this->id ];


        if (! $this->votes()->where($attr)->exists() ){
            return $this->votes()->create($attr);
        }
    }

    public function isVoted() {
        $attr = ['user_id' => auth()->id(),
                 'post_id' => $this->id ];

        return $this->votes()->where($attr)->exists();
    }
}
