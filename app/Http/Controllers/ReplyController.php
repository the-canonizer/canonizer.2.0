<?php

namespace App\Http\Controllers;

use App\CThread;
use App\Reply;

use App\CommonForumFunctions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


/**
 * ReplyController Class Doc Comment
 *
 * @category Class
 * @package  MyPackage
 * @author   Ashutosh Kukreti <kukreti.ashutosh@gmail.com>
 * @license  GNU General Public License
 * @link     http://example.com
 */

class ReplyController extends Controller
{
    /**
     * Constructor
     *
     * @return Auth middleware required to reply
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }
     /**
     * Show the form for editing the specified resource.
     *
     * @parameter \App\CThread  $CThread
     * @return    \Illuminate\Http\Response
     */  
    public function store($topicid, $topicname, $campnum, $threadId, request $request)
    {
        $valMessages = [
            'body.required' => 'Body field is required.',
            'nick_name.required' => 'Nick name field is required.',
        ];
         $this->validate(
             $request, [
                'body' => 'required',
                'nick_name' => 'required'
             ],
             $valMessages
         );
        /**
        * The below code is used to save the data into the database.
        */

        if($request['reply_id']!=""){
            $reply = reply::where('id', $request['reply_id'])->first();
            $msg= "Post Updated Successfully!'";
        }
        else{
            $reply = new reply;
            $msg= "Post Created Successfully!'";
        }

        $reply->body = request('body');
        $reply->user_id = request('nick_name');
        $reply->c_thread_id = $threadId;
        $reply->save();

        // Return Url after creating thread Successfully
        $return_url = '/forum/'.$topicid.'-'.$topicname.'/'.$campnum.'/threads/'.$threadId;

        CommonForumFunctions::sendEmailToSupportersForumPost($topicid, $campnum, $return_url,request('body'), $threadId, request('nick_name'), $topicname,request('reply_id'));
        return redirect($return_url)->with('success', $msg);

       // return back();
    }
}
