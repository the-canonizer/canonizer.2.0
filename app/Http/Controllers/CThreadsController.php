<?php 
namespace App\Http\Controllers;

use DB;
use App\CThread;
use App\Model\Camp;
use App\Model\Topic;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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
            $threads = CThread::where('camp_id', $campnum)->
                                where('topic_id', $topicid)->latest()->get();
        }
        else { 
            return (
                'Validation Error!!!!'. 
                'Either Topic ID is not related to Camp or Invalid Topic Name.'
            ); 
        }
        
        $topic = getArray($topicid, $topicname, $campnum);

        /* Old view of retuning of the views
        * the below view doesn't return the name of the Camp. 
        * from the users perspective it would be good if the name of the camp
        * is shown on the page.
        return view('threads.index', $topic, compact('threads'));
        */
        // New View
        return view(
            'threads.index',
            $topic, 
            [
                'threads'          => $threads,
                // Return the name of the camp to index View
                //'campname'         => Camp::find($campnum)->camp_name,
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
                //'campname'         => Camp::find($campnum)->camp_name,
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
        $topic = getArray($topicid, $topicname, $campnum);

        return view(
            'threads.create', 
            $topic, 
            compact('threads')
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
        //dd($request->all());
        //dd($campnum, $topicid);
        //Validate the request for Error Handling

        $this->validate(
            $request, [
                'title'    => 'required',
                'body'     => 'required',
            ]
        );

        $thread = CThread::create(
            [
            'user_id'  => auth()->id(),
            'title'    => request('title'),
            'body'     => request('body'),
            'camp_id'  => $campnum,
            'topic_id' => $topicid
            ]
        ); 
        return back();
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
        return view(
            'threads.show', 
            $topic, [
                'threads' => CThread::findOrFail($CThread),
                'replies' => CThread::findOrFail($CThread)
                                            ->replies()
                                            ->paginate(10),
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @parameter \App\CThread  $CThread
     * @return    \Illuminate\Http\Response
     */
    public function edit(CThread $CThread)
    {
        //
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
