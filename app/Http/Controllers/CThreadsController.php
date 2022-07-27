<?php
namespace App\Http\Controllers; 

use DB;
use App\CThread;
use App\Reply;
use App\Model\Camp;
use App\Model\Topic;
use App\Model\Nickname;

use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Mail\ForumThreadCreatedMail;
use App\Model\Support;
use App\CommonForumFunctions;
use Illuminate\View\View;

/**
 * CThreadsController Class Doc Comment
 *
 * @category Class
 * @package  MyPackage
 * @author   Ashutosh Kukreti <kukreti.ashutosh@gmail.com>
 * @license  GNU General Public License
 * @link     http://example.com
 */

class CThreadsController extends Controller
{
    /**
     * Auth middleware is applied to all except index and show
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($topicid, $topicname, $campnum)
    { 

        if ((camp::where('camp_num', $campnum)->where('topic_num', $topicid)->value('camp_name')))
        {
            $partcipateFlag = 0;
            $myThreads = 0;
            if (Auth::check()) {
                $request_by = request('by');
            }else{
                $request_by = "";
            }
            if ($request_by == 'me') {
                /**
                 * Filter out the Threads by User
                 * @var [type]
                 */
                $userNicknames = Nickname::topicNicknameUsed($topicid)->sortBy('nick_name');
                $myThreads = 1;

                if (count($userNicknames) > 0) {
                    $threads = CThread::where('camp_id', $campnum)->
                                        where('topic_id', $topicid)->
                                        where('user_id', $userNicknames[0]->id)->
                                        latest()->paginate(10);
                }
                else {
                    $threads = [];
                }
            }
            elseif ($request_by == 'participate') {
                /**
                 * Filter out the threads on the basis of users Participation in Threads
                 * @var [type]
                 */
                $userNicknames = Nickname::topicNicknameUsed($topicid);

                if (count($userNicknames) > 0) {
                    $threads = CThread::leftJoin('post', 'thread.id', '=', 'post.c_thread_id' )->
                                        select('thread.*', 'post.body')->
                                        where('camp_id', $campnum)->
                                        where('topic_id', $topicid)->
                                        where('post.user_id', $userNicknames[0]->id)->groupBy('thread.id')->
                                        latest()->paginate(20);
                }
                else {
                    $threads = [];
                }
                $partcipateFlag = 1;
                //dd($threads);
            }
            elseif ($request_by == 'most_replies') {
                /**
                 * Filter out the threads on the basis of most replies or the most popular threads
                 * @var [type]
                 */
                $threads = CThread::join('post', 'thread.id', '=', 'post.c_thread_id' )->
                  select('thread.*', DB::raw('count(post.c_thread_id) as post_count')) ->
                  where('camp_id', $campnum)->
                  where('topic_id', $topicid)->
                  groupBy('thread.id')->
                  orderBy('post_count', 'desc')->
                  latest()->paginate(10);
            }
            else {
                /**
                 * Filter out the threads on the basis of the latest creation dates
                 * @var [type]
                 */
                $threads = CThread::where('camp_id', $campnum)->
                  where('topic_id', $topicid)->
                  latest()->paginate(10);
            }
        }
        else {
            return (
                'Validation Error!!!!'.
                'Topic ID is not related to Camp.'
            );
        }

        /**
         * As of filters should not be applied on the camp forum pages
         * ticket # 1427 - Muhammad Ahmed
         */
        session()->forget('asofDefault');
        $liveTopic = getAgreementTopic($topic->topic_num);

        $topic = getArray($topicid, $topicname, $campnum);
        $camp  = Camp::getLiveCamp($topicid,$campnum);
        $threadTopic = Topic::where('topic_num', $topicid)
                ->where('go_live_time', '<=', time())
				->where('objector_nick_id','=',NULL)
                ->latest('submit_time')
                ->first();

        return view(
            'threads.index',
            $topic,
            [
                'threads'          => $threads,
                'myThreads'        => $myThreads,
                // Return the name of the camp to index View
                'campname'         => camp::where('camp_num', $campnum)
                                                    ->where('topic_num', $topicid)
                                                    ->value('camp_name'),
                // Return the name of the Topic to index View
                'topicGeneralName' => $liveTopic->topic_name ?? $threadTopic->topic_name,
                'namespace_id'     => $threadTopic->namespace_id,
                'parentcamp'       => Camp::campNameWithAncestors($camp,'',$topicname),
                'participateFlag'  => $partcipateFlag,
                'request_by'  => $request_by,
            ],
            compact('threads')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function topicindex($topicid, $topicname) {

        if (Topic::find($topicid)->topic_name){
            $threads = CThread::where('topic_id', $topicid)->latest()->get();
        }
        else {
            return (
                'Validation Error!!!!'.
                'Either Topic ID is not related to Camp or Invalid Topic Name.'
            );
        }

        $campnum = 1;

        $topic = getArray($topicid, $topicname, 1);

        return view(
            'threads.index',
            $topic,
            [
                'threads'          => $threads,
                // Return the name of the camp to index View
                'campname'         => camp::where('camp_num', $campnum)
                                            ->where('topic_num', $topicid)
                                            ->value('camp_name'),
                // Return the name of the Topic to index View
                'topicGeneralName' => Topic::find($topicid)->topic_name
            ],
            compact('threads')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($topicid, $topicname, $campnum)
    {
        $userNicknames = Nickname::topicNicknameUsed($topicid);

        $topic = getArray($topicid, $topicname, $campnum);

        $camp  = Camp::getLiveCamp($topicid,$campnum);

        return view(
            'threads.create',
            $topic,
            [
                'parentcamp'    => Camp::campNameWithAncestors($camp,'',$topicname),
                'userNicknames'   => $userNicknames,
                'topicGeneralName' => $camp->topic->topic_name,

            ],
            compact('threads', 'topicGeneralName')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @parameter \Illuminate\Http\Request  $request
     * @return    \Illuminate\Http\Response
     */
    public function store(Request $request, $topicid, $topicname, $campnum)
    {
        $title = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ",  request('title'))));
         
        //Validate the request for Error Handling
        $thread_flag = CThread::where('camp_id', $campnum)->
                                where('topic_id', $topicid)->
                                where('title', $title)->get();
        $messagesVal = [
            'title.regex' => 'Title can only contain space and alphanumeric characters.',
            'title.required' => 'Title is required.',
            'title.max' => 'Title can not be more than 100 characters.',
            'nick_name.required' => 'The nick name field is required.',
        ];
        //993 ticket
          $this->validate(
              $request, [
                  'title'    => 'required|max:100|regex:/^[a-zA-Z0-9\s]+$/',
                  'nick_name' => 'required'
              ],$messagesVal
          );
        
        if (count($thread_flag) > 0) {

            // Return Url if thread name found
            $return_url = 'forum/'.$topicid.'-'.$topicname.'/'.$campnum.'/threads/create';

            return redirect($return_url)->with('error', ' Thread title must be unique!');
        }

        $thread = CThread::create(
            [
            'user_id'  => request('nick_name'),
            'title'    => $title,
            'body'     => request('title'),
            'camp_id'  => $campnum,
            'topic_id' => $topicid
            ]
        );

        // Return Url after creating thread Successfully
        $return_url = 'forum/'.$topicid.'-'.$topicname.'/'.$campnum.'/threads'; //create
        
        CommonForumFunctions::sendEmailToSupportersForumThread($topicid, $campnum,
                              $return_url, request('title'), request('nick_name'), $topicname);

        return redirect($return_url)->with('success', 'Thread Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @parameter $CThread
     * @return Application|Factory|View
     */
    public function show($topicid, $topicname, $campnum, $CThread,$reply_id=null)
    {
        $topic = getArray($topicid, $topicname, $campnum);
        $camp  = Camp::getLiveCamp($topicid,$campnum);
        if($reply_id!=null){
          $replies=Reply::findOrFail($reply_id);
        }
        else{
          $replies=CThread::findOrFail($CThread)->replies()->paginate(10);
        }

        $userNickNames = (auth()->check()) ? Nickname::topicNicknameUsed($topicid) : array();

        return view(
            'threads.show',
             $topic, [
                'parentcamp'    => Camp::campNameWithAncestors($camp,'',$topicname),
                'userNicknames' => $userNickNames,
                'threads' => CThread::findOrFail($CThread),
                'topicGeneralName' => $camp->topic->topic_name,
                'replies' => $replies,
                'reply_id' => $reply_id,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @parameter \App\CThread  $CThread
     * @return    \Illuminate\Http\Response
     */
    public function edit($topicName, $campNum, $threadId) {
      return view(
        'threads.edit',
        [
          'topicName' => $topicName,
          'campNum'   => $campNum,
          'thread'    => CThread::findOrFail($threadId)
        ]
      );
    }

    public function save_title(Request $request, $topicName, $campNum, $threadId) {


        $campArr = preg_split("/[-]/", $campNum);
        $topicArr = preg_split("/[-]/", $topicName);
       
        $request->title = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ",  request('title'))));
        $title  = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ",  request('title'))));
        $old_title = request('thread_title_name');
        $messagesVal = [
            'title.regex' => 'Title must only contain space and alphanumeric characters.',
            'title.required' => 'Title is required.',
            'title.unique'   => 'Thread title must be unique'
        ];

          $this->validate(
            $request, [
                'title' => [
                    'required', 'regex:/^[a-zA-Z0-9\s]+$/', 'max:100', Rule::unique('thread')->ignore($threadId)
                ],
            ], $messagesVal
          );

          //Validate the request for Error Handling
          $thread_flag = CThread::where('camp_id', $campArr[0])->
            where('topic_id', $topicArr[0])->
            where('title', $title)->get();
          //print_r(count($thread_flag) );die;
          if (count($thread_flag) == 0) {
            
            DB::update('update thread set title =?, updated_at = ? where id = ?', [$title, time(), $threadId]);

            $return_url = 'forum/'.$topicName.'/'.$campNum.'/threads/';//.$threadId.'/edit';
            
            return redirect($return_url)->with('success', ' Thread title updated.');
          }
          else {
            $return_url = 'forum/'.$topicName.'/'.$campNum.'/threads/'.$threadId.'/edit';
            return redirect($return_url)->with('error', ' Thread title must be unique!');
          }
          //return redirect($return_url)->with('success', 'Thread title updated.');

    }
 

    /**
     * Update the specified resource in storage.
     *
     * @parameter \Illuminate\Http\Request  $request
     * @parameter \App\CThread  $CThread
     * @return    \Illuminate\Http\Response
     */
    public function update(Request $request, CThread $CThread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @parameter \App\CThread  $CThread
     * @return    \Illuminate\Http\Response
     */
    public function destroy(CThread $CThread)
    {
        //
    } 
}

/**
 * Return the array to the respective callers to save time
 *
 * @parameter integer  $topicid
 */
function getArray($topicid, $topicname, $campnum)
{
    return array(
        'topicname' => $topicid.'-'.$topicname,
        'campnum'   => $campnum,
    );
}


?>
