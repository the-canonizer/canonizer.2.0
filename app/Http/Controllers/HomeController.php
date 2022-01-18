<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Model\Camp;
use App\Model\Topic;
use App\Facades\Util;
use App\VideoPodcast;
use App\Model\Support;
use App\Model\Namespaces;
use App\Model\TopicSupport;
use Illuminate\Http\Request;
use App\Jobs\CanonizerService;
use App\Model\SupportInstance;

class HomeController extends Controller {

    public function __construct() {
        parent::__construct();
        if (Auth::check()) {
            if (!session('defaultUserAlgo')) {
                $defaultAlgo = Auth::user()->default_algo;
                 session()->put('defaultAlgo',$defaultAlgo);
                 session()->put('defaultUserAlgo',$defaultAlgo);
            }
         }
          if(isset($_REQUEST['asof']) && $_REQUEST['asof']!=''){
                //session(['asofDefault'=>$_REQUEST['asof']]);
                session()->put('asofDefault',$_REQUEST['asof']);
            }
            if(isset($_REQUEST['asofdate']) && $_REQUEST['asofdate']!=''){
                //session(['asofdateDefault'=>$_REQUEST['asofdate']]);
                session()->put('asofdateDefault',$_REQUEST['asofdate']);
            }
            session()->save();
    }

    public function index(Request $request, $params = null) {
        
		//session()->flush();
        $namespaces = Namespaces::all();
         if(null == session('defaultNamespaceId')){
            session()->put('defaultNamespaceId',1);
        }
        $page_no = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        
        ### Added by ali Ahmad
        ### Canonizer Service ticket CS17
        ### Date : 18-01-2022
        
        $page_per_record = env('PAGE_PER_RECORD');
        $page_per_record = isset($page_per_record) ? $page_per_record: 20;
        $cronDate = env('CS_CRON_DATE'); 
        $cronDate =  isset($cronDate) ? strtotime($cronDate) : strtotime(date('Y-m-d'));

        $asOf = 'default';

        if((isset($_REQUEST['asof']) && ($_REQUEST['asof'] == "review" || $_REQUEST['asof'] == "bydate"))){
            $asOf = $_REQUEST['asof'];
        }
        else if ((session('asofDefault')== "review" || session('asofDefault')== "bydate" ) && !isset($_REQUEST['asof'])) {
            $asOf = session('asofDefault');
        }

        $asOfDefaultDate = date('Y-m-d');

        if(isset($_REQUEST['asof']) && $_REQUEST['asof'] == "bydate"){
            $asOfDefaultDate = date('Y-m-d', strtotime($_REQUEST['asofdate']));
         }else if(($asOf == 'bydate') && session('asofdateDefault')){
            $asOfDefaultDate =  session('asofdateDefault');
         }

        $asOfDefaultDate = strtotime($asOfDefaultDate);

        $previous = 0;

        $selectedAlgo = 'blind_popularity';
        if(session('defaultAlgo')) {
            $selectedAlgo = session('defaultAlgo');
        }
       
        if( ($asOfDefaultDate > $cronDate) && ( $selectedAlgo == 'blind_popularity' || $selectedAlgo == "mind_experts")){

            $previous = 1; 

            $requestBody = [
                "page_number" => $page_no,
                "page_size" => $page_per_record,
                "namespace_id" => session('defaultNamespaceId'),
                "asofdate" =>  $asOfDefaultDate,
                "algorithm" => $selectedAlgo,
                "search" => "Hard"     
            ];

            $appURL = env('CS_APP_URL');
            $endpointCSGETTree =   env('CS_GET_HOME_PAGE_DATA');
            $endpoint = $appURL."/".$endpointCSGETTree;
            $headers = array('Content-Type:multipart/form-data');

            $reducedTree = Util::execute('POST', $endpoint, $headers, $requestBody);

            $topics = json_decode($reducedTree, true);
        }
        else
        {
            $topics =  Camp::sortTopicsBasedOnScore(Camp::getAllAgreementTopic(20, $_REQUEST));
        }

        ### End of CS17 ticket ####
        
        $videopodcast = VideoPodcast::all()->first();
        return view('welcome', ['topics' => $topics, 'namespaces' => $namespaces,'videopodcast'=>$videopodcast, "previous"=>$previous, "page_no" => $page_no]);
    }

    public function loadmoretopics(Request $request){
        if($request->ajax())
         {
           $topics =  Camp::sortTopicsBasedOnScore(Camp::getAllAgreementTopic(20, $_REQUEST));
            foreach ($topics as $k => $topicdata) {
            $topic = \App\Model\Topic::where('topic_num','=',$topicdata->topic_num)->latest('submit_time')->get();
            $topic_name_space_id = isset($topic[0]) ? $topic[0]->namespace_id:1;
            $request_namesapce = session('defaultNamespaceId', 1);
            // if($topic_name_space_id !='' && $topic_name_space_id != $request_namesapce){
            //     continue;
            // }
            $output .= $topicdata->campTreeHtml();
            }
            ($output != '') ? $output .= '<a id="btn-more" class="remove-row" data-id="' . $topicdata->id . '"></a>' : '';

            echo $output;
         }else{
            echo "";
         }
    }

    public function loadtopic(Request $request) {

        $output = '';
        $id = $request->id;
        $topics = Camp::getAllLoadMoreTopic($request->offset, $_REQUEST, $id);

                                

        foreach ($topics as $k => $topicdata) {
            $topic = \App\Model\Topic::where('topic_num','=',$topicdata->topic_num)->latest('submit_time')->get();
            $topic_name_space_id = isset($topic[0]) ? $topic[0]->namespace_id:1;
            $request_namesapce = session('defaultNamespaceId', 1);
            // if($topic_name_space_id !='' && $topic_name_space_id != $request_namesapce){
            //     continue;
            // }
            $output .= $topicdata->campTreeHtml();
        }
        ($output != '') ? $output .= '<a id="btn-more" class="remove-row" data-id="' . $topicdata->id . '"></a>' : '';

        echo $output;
    }

    public function browse(Request $request) {
        if(empty($_REQUEST['namespace']) && session()->has('defaultNamespaceId')){
            session()->forget('defaultNamespaceId');
        }
        $topics = Camp::getBrowseTopic();
        $namespaces = Namespaces::all();
        return view('browse', compact('topics', 'namespaces'));
    }

    public function recusriveCampDisp($childs) {
        foreach ($childs as $child) {
             echo "child --" . $child->title . "<br/>";
            if (count($child->childrens($child->topic_num, $child->camp_num)) > 0) {
                $this->recusriveCampDisp($child->childrens($child->topic_num, $child->camp_num));
            }
        }
    }

    public function supportmigration() {

        if (!isset($_REQUEST['instance'])) {
            $counter = 0;
            $stored = array();
            $topics = Support::where('delegate_nick_name_id', '!=', 0)->groupBy('topic_num', 'nick_name_id')->orderBy('start', 'DESC')->get();

            $topics1 = Support::groupBy('topic_num', 'nick_name_id')->orderBy('start', 'DESC')->get();

            $alreadyMigrated = TopicSupport::get();
            //echo "total topic migrating is :".count($topics)." </br>";

            if (count($alreadyMigrated) == 0) {
                foreach ($topics as $key => $topic) {

                    $topicSupport = new TopicSupport();

                    $topicSupport->topic_num = $topic->topic_num;
                    $topicSupport->nick_name_id = $topic->nick_name_id;
                    $topicSupport->delegate_nick_id = $topic->delegate_nick_name_id;
                    $topicSupport->submit_time = $topic->start;

                    $topicSupport->save();

                    $stored[] = $topic->topic_num . '-' . $topic->nick_name_id;

                    $counter++;
                }

                foreach ($topics1 as $key => $topic) {


                    if (!in_array($topic->topic_num . '-' . $topic->nick_name_id, $stored)) {
                        $topicSupport = new TopicSupport();

                        $topicSupport->topic_num = $topic->topic_num;
                        $topicSupport->nick_name_id = $topic->nick_name_id;
                        $topicSupport->delegate_nick_id = $topic->delegate_nick_name_id;
                        $topicSupport->submit_time = $topic->start;

                        $topicSupport->save();


                        $counter++;
                    }
                }
                echo "total " . $counter . " topic migrated.";
                exit;
            } else {
                echo "Data already migrated.";
                exit;
            }
        } else {

            $topic = TopicSupport::orderBy('id', 'ASC')->get();

            $alreadyMigrated = SupportInstance::get();

            if (count($topic) > 0 && count($alreadyMigrated) == 0) {

                foreach ($topic as $data) {

                    $supportCamp = Support::where('topic_num', $data->topic_num)->where('nick_name_id', $data->nick_name_id)->orderBy('support_order', 'ASC')->get();

                    foreach ($supportCamp as $d) {

                        $sinstance = new SupportInstance();

                        $sinstance->topic_support_id = $data->id;
                        $sinstance->camp_num = $d->camp_num;
                        $sinstance->support_order = $d->support_order + 1;
                        $sinstance->submit_time = $d->start;
                        $sinstance->status = 0;

                        $sinstance->save();
                    }
                }

                echo "Data migrated successfully....";
                exit;
            } else {

                echo "There is no topic available to migrate or data already migrated.";
                exit;
            }
        }
    }

    public function changeAlgorithm(Request $request) {
        $algorithm = $request->input('algo');
        $tempTopicID = $request->input('topic_id');
        
        session(['defaultAlgo' => $algorithm]);

        // if(isset($tempTopicID) && !empty($tempTopicID)) {
        //     $topicNum = explode('-',$tempTopicID)[0];
        //     $topic = Topic::getLiveTopic($topicNum);
        //     if(isset($topic) && (strtolower($algorithm) === 'blind_popularity' || strtolower($algorithm) === 'mind_experts')){
        //         Util::dispatchJob($topic, 1, 1);
        //     } else {
        //         // Nothing to do
        //     }
        // }
    }

    public function changeNamespace(Request $request) {
        $namespace = Namespaces::find($request->input('namespace'));
        session(['defaultNamespaceId' => $namespace->id]);
    }

    public function termservice(){
            return view('termservice');

    }

    public function privacypolicy(){
            return view('privacypolicy');
    }

}
