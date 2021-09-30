<?php
namespace App\Http\Controllers;

use DB;
use App\CThread;
use App\Reply;
use App\Model\Camp;
use App\Model\Topic;
use App\Model\Nickname;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

use App\Mail\ForumThreadCreatedMail;
use App\Model\Support;
use App\CommonForumFunctions;

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
            if (request('by') == 'me') {
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
            elseif (request('by') == 'participate') {
                /**
                 * Filter out the threads on the basis of users Participation in Threads
                 * @var [type]
                 */
                $userNicknames = Nickname::topicNicknameUsed($topicid);

                if (count($userNicknames) > 0) {
                    $threads = CThread::join('post', 'thread.id', '=', 'post.c_thread_id' )->
                                        select('thread.*', 'post.body')->
                                        where('camp_id', $campnum)->
                                        where('topic_id', $topicid)->
                                        where('post.user_id', $userNicknames[0]->id)->
                                        latest()->paginate(20);
                }
                else {
                    $threads = [];
                }
                $partcipateFlag = 1;
                //dd($threads);
            }
            elseif (request('by') == 'most_replies') {
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

        $topic = getArray($topicid, $topicname, $campnum);
        $camp  = Camp::getLiveCamp($topicid,$campnum);

        //dd($partcipateFlag);
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
                'topicGeneralName' => Topic::where('topic_num', $topicid)
                                             ->where('go_live_time', '<=', time())
											 ->where('objector_nick_id','=',NULL)
                                             ->latest('submit_time')
                                             ->first()->topic_name,
                'parentcamp'       => Camp::campNameWithAncestors($camp,'',$topicname),
                'participateFlag'  => $partcipateFlag,
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
                'userNicknames'   => $userNicknames

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
        //Validate the request for Error Handling
        $thread_flag = CThread::where('camp_id', $campnum)->
                                where('topic_id', $topicid)->
                                where('title', $request->{'title'})->get();
        $messagesVal = [
            'title.regex' => 'Title must only contain space and alphanumeric characters.',
            'title.required' => 'Title is required.',
            'nick_name.required' => 'The nick name field is required.',
        ];
          $this->validate(
              $request, [
                  'title'    => 'required|max:100|regex:/^[a-zA-Z0-9\s]+$/',
                  'nick_name' => 'required'
              ],$messagesVal
          );

        if (count($thread_flag) > 0) {

            // Return Url if thread name found
            $return_url = 'forum/'.$topicid.'-'.$topicname.'/'.$campnum.'/threads/create';

            return redirect($return_url)->with('error', 'Thread title must be unique!');
        }

        $thread = CThread::create(
            [
            'user_id'  => request('nick_name'),
            'title'    => request('title'),
            'body'     => request('title'),
            'camp_id'  => $campnum,
            'topic_id' => $topicid
            ]
        );

        // Return Url after creating thread Successfully
        $return_url = 'forum/'.$topicid.'-'.$topicname.'/'.$campnum.'/threads';

        CommonForumFunctions::sendEmailToSupportersForumThread($topicid, $campnum,
                              $return_url, request('title'), request('nick_name'), $topicname);

        return redirect($return_url)->with('success', 'Thread Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @parameter $CThread
     * @return    \Illuminate\Http\Response
     */
    public function show($topicid, $topicname, $campnum, $CThread)
    {
        $topic = getArray($topicid, $topicname, $campnum);
        $camp  = Camp::getLiveCamp($topicid,$campnum);
        return view(
            'threads.show',
             $topic, [
                'parentcamp'       => Camp::campNameWithAncestors($camp,'',$topicname),
                'userNicknames' => (auth()->check()) ? Nickname::topicNicknameUsed($topicid) : array(),
                'threads' => CThread::findOrFail($CThread),
                'replies' => CThread::findOrFail($CThread)->replies()->paginate(10),
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

        $messagesVal = [
            'title.regex' => 'Title must only contain space and alphanumeric characters.',
            'title.required' => 'Title is required.',
            'title.unique'   => 'Thread title must be unique'
        ];

          $this->validate(
            $request, [
                'title' => [
                    'required', 'max:100', Rule::unique('thread')
                        ->where(function ($query) use ($campArr, $topicArr) {
                            return $query->where('camp_id', $campArr[0])->where('topic_id', $topicArr[0]);
                        })
                ],
            ], $messagesVal
          );

          $title = request('title');
          DB::update('update thread set title =? where id = ?', [$title, $threadId]);

          $return_url = 'forum/'.$topicName.'/'.$campNum.'/threads/'.$threadId.'/edit';

          return redirect($return_url)->with('success', 'Thread title updated.');

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
