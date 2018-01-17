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
        
        return $this->hasMany(Reply::class);
    }

    /**
     * The below function for the Thread belongs to a User 
     * 
     * @return threads belongs to a User
     */
    public function creator() 
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Implies that thread belongs to Camp
     * 
     * @return relationship between thread and camp
    */
    public function camp()
    {
        return $this->belogsTo(Camp::class);
    }

    /* 
    // Not in used but can be used in future to get the Thread ID
    public function getThreadId() 
    {
        return $this->id;
    } */
}
