<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * CThread Class Doc Comment
 *
 * @category Class
 * @package  MyPackage
 * @author   Ashutosh Kukreti <kukreti.ashutosh@gmail.com>
 * @license  GNU General Public License
 * @link     http://example.com
 */

class CThread extends Model
{
    protected $table = 'thread';
    protected $guarded = [];
    /**
     * The Below functions is for defining
     * Many to Many Relation with Replies
     */
    public $primaryKey = 'id';

    /**
     * Path of the Forum
     *
     * @return current path
     */
    public function path()
    {
         return '/threads/'. $this->id;
    }

    /**
     * Thread has many to many relationship with replies
     *
     * @return Relationship between thread and replies
     */
    public function replies()
    {

        return $this->hasMany(Reply::class)->latest();
    }

    /**
     * The below function for the Thread belongs to a User
     *
     * @return threads belongs to a User
     */
    public function creator()
    {
        return $this->belongsTo('App\Model\Nickname'::class, 'user_id');
    }

    /**
     * Implies that thread belongs to Camp
     *
     * @return relationship between thread and camp
    */
    public function camp()
    {
        return $this->belongsTo('App\Model\Camp'::class,'camp_id');
    }

    /*
    // Not in used but can be used in future to get the Thread ID
    public function getThreadId()
    {
        return $this->id;
    } */
}
