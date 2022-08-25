<?php

namespace App\Helpers;

use Exception;
use App\Model\Camp;
use App\Model\Topic;
use App\Model\Support;
use App\Jobs\CanonizerService;
use App\Model\Namespaces;
use Illuminate\Support\Facades\Log;

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
    public function dispatchJob($topic, $campNum = 1, $updateAll = 0, $is_disabled = 0, $is_one_level = 0) {

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
                "is_disabled" => $is_disabled,
                "is_one_level" => $is_one_level
            ];

           // dd($canonizerServiceData);
            // Dispact job when create a camp
            CanonizerService::dispatch($canonizerServiceData)
                ->onQueue('canonizer-service')
                ->unique(Topic::class, $topic->topic_num);

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
                            'updateAll' => 1,
                            "is_disabled" => $is_disabled,
                            "is_one_level" => $is_one_level
                        ];
                        // Dispact job when create a camp
                        CanonizerService::dispatch($canonizerServiceData)
                            ->onQueue('canonizer-service')
                            ->unique(Topic::class, $topic->topic_num);
                    }
                }
            }
        } catch(Exception $ex) {
            Log::error("Util :: DispatchJob :: message: ".$ex->getMessage());
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
}