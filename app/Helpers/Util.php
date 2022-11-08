<?php

namespace App\Helpers;

use Exception;
use App\Model\Camp;
use App\Model\Topic;
use App\Model\Support;
use App\Jobs\CanonizerService;
use App\Model\Namespaces;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/*
|=================================================================
| @Class        :   Util
| @Description  :   List all utility methods of Application
| @Author       :   Ashish Upadhyay
| @Created_at   :   14-Dec-2021
| @Modified_at  :
| @Version      :   1.0
|=================================================================
*/

class Util
{
    /**
     * Excute the http calls 
     * @param string $type (GET|POST|PUT|DELETE)
     * @param string $url
     * @param string $headers (Optional)
     * @param array $body (Optional)
     * @return mixed
     */
    public function execute($type, $url, $headers=null, $body=null) {
        $options = array(
            CURLOPT_URL             => $url,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_TIMEOUT         => 30,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => $type,
            CURLOPT_POSTFIELDS      => $body,
            CURLOPT_HTTPHEADER      => $headers
        );
        
        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $curl_response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return null;
        } 
        else {
            //$curl_result_obj = $curl_response;
            return $curl_response;
        }
    }

     /**
     * Dispatch canonizer service job
     * @param object $topic
     * @param boolean $updateAll
     * @return void
     */
    public function dispatchJob($topic, $campNum = 1, $updateAll = 0, $delay = null, $campChangeID = null) {
        try{
            $selectedAlgo = 'blind_popularity';
            if(session('defaultAlgo')) {
                $selectedAlgo = session('defaultAlgo');
            }
            
            $asOf = 'default';
            if(session('asofDefault')) {
                $asOf = session('asofDefault');
            }

            $asOfDefaultDate = time();
            $canonizerServiceData = [
                'topic_num' =>  $topic->topic_num,
                'algorithm' => $selectedAlgo,
                'asOfDate'  => $asOfDefaultDate,
                'asOf'      => $asOf,
                'updateAll' => $updateAll,
                'campChangeID' => $campChangeID
            ];

           // dd($canonizerServiceData);
            // Dispact job when create a camp
            if ($delay) {
                // Job delay coming in seconds, update the service asOfDate for delay job execution.
                $delayTime = Carbon::now()->addSeconds($delay);
                $canonizerServiceData['asOfDate'] = $delayTime->timestamp;
                CanonizerService::dispatch($canonizerServiceData)->delay($delayTime)->onQueue(config('app.DELAY_QUEUE_SERVICE_NAME'));
            } else {
                CanonizerService::dispatch($canonizerServiceData)->onQueue(config('app.QUEUE_SERVICE_NAME'))->unique(Topic::class, $topic->topic_num);
            }
            
            // Incase the topic is mind expert then find all the affected topics 
            if($topic->topic_num == 81) {
                $camp = Camp::where('topic_num', $topic->topic_num)->where('camp_num', '=', $campNum)->where('go_live_time', '<=', time())->latest('submit_time')->first();
                if(!empty($camp)) {
                    // Get submitter nick name id
                    $submitterNickNameID = $camp->camp_about_nick_id;
                    $affectedTopicNums = Support::where('nick_name_id',$submitterNickNameID)->where('end',0)->distinct('topic_num')->pluck('topic_num');
                    foreach($affectedTopicNums as $affectedTopicNum) {
                        $topic = Topic::where('topic_num', $affectedTopicNum)->get()->last();
                        $canonizerServiceData = [
                            'topic_num' => $topic->topic_num,
                            'algorithm' => $selectedAlgo,
                            'asOfDate'  => $asOfDefaultDate,
                            'asOf'      => $asOf,
                            'updateAll' => 1
                        ];
                        // Dispact job when create a camp
                        CanonizerService::dispatch($canonizerServiceData)
                            ->onQueue(config('app.QUEUE_SERVICE_NAME'))
                            ->unique(Topic::class, $topic->topic_num);
                    }
                }
            }
        } catch(Exception $ex) {
            Log::error("Util :: DispatchJob :: message: ".$ex->getMessage());
        }
        
    }

    /**
     * This function only work when we changes parent camp.
     * @param int $campChangeId
     */
    public function checkParentCampChanged($changeID) {
        $camp = Camp::where('id', $changeID)->first();
        if(!empty($camp)) {
            $topic_num = $camp->topic_num;
            $camp_num = $camp->camp_num;
            $parent_camp_num = $camp->parent_camp_num;
            $in_review_status=true;
            //We have fetched new live camp record
            $livecamp = Camp::getLiveCamp($topic_num,$camp_num);

            if(isset($parent_camp_num) && $parent_camp_num!=''){
                if($in_review_status){
                    //We have feched all parent camps hierarchy based on changed camp (#1262 ,#1191)
                    $allParentCamps = Camp::getAllParent($livecamp);

                    //We have feched all supporters based on all parent camps hierarchy 
                    $allParentsSupporters = Support::where('topic_num',$topic_num)
                        ->where('end',0)
                        ->whereIn('camp_num',$allParentCamps)
                        ->pluck('nick_name_id');

                    //We have feched all child camps hierarchy based on current live camp
                    $allChildCamps = Camp::getAllChildCamps($livecamp);

                    
                    if(sizeof($allParentsSupporters) > 0) {
                        foreach($allParentCamps as $parent) {
                            Support::removeSupport($topic_num, $parent, $allParentsSupporters, $allChildCamps);
                        }
                    }
                }
            }
        }
    }


     /**
     * getEmailSubjectForSandbox
     * @param int $namespace_id
     */
    public function getEmailSubjectForSandbox($namespace_id)
    {
        try {
            $subject = 'canon';
            $namespace = Namespaces::find($namespace_id);
            if(preg_match('/sandbox/i',$namespace->name)){
                $subject = 'canon/sandbox/';
            }
            if(preg_match('/sandbox testing/i',$namespace->name)){
                $subject = 'canon/sandbox testing/';
            }
            return (env('APP_ENV', 'development') == 'staging' || (env('APP_ENV', 'development') == 'local')) ? (env('APP_ENV', 'development') == 'local') ? '[local.' . $subject . ']' : '[staging.' . $subject . ']' : '[' . $subject . ']';
        } catch (Exception $ex) {
            Log::error("Util :: GetEmailSubjectForSandbox :: message: " . $ex->getMessage());
        }
    }

     /**
     * @param $id
     * @return String
     */
    public static function canon_encode($id=''):string
    {
        $code = 'Malia' . $id . 'Malia';
        $code = base64_encode($code);
        return $code;
    }

    /**
     * @param $code
     * @return int
     */
    public static function canon_decode($code = ''):int
    {
        $code = base64_decode($code);
        return (int) $code=str_replace("Malia","",$code);
    }

    public static function generateShortCode($file, $shortCode = '') 
    {
        if(!$shortCode) {			
            $shortCode = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);	
        } 
        
        return $shortCode;
    }
}