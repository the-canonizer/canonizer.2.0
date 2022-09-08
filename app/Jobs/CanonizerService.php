<?php

namespace App\Jobs;

use App\Facades\Util;
use App\Model\ProcessedJob;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Com\NickelIT\UniqueableJobs\Dispatchable;
use Com\NickelIT\UniqueableJobs\Uniqueable;

class CanonizerService implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Uniqueable;

    private $canonizerData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->canonizerData = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get payload of the job to log
        $jobPayload = $this->job->getRawBody();
        $updateAll = 0;

        if(!$this->validateRequestData($this->canonizerData)) {
            return;
        }
        if(array_key_exists('updateAll', $this->canonizerData)) {
            $updateAll = $this->canonizerData['updateAll'];
        }
        if(!empty($this->canonizerData['campChangeID'])) {
            Util::checkParentCampChanged($this->canonizerData['campChangeID']);
        }
        
        $requestBody = [
            'topic_num'     => $this->canonizerData['topic_num'],
            'asofdate'      => $this->canonizerData['asOfDate'],
            'algorithm'     => $this->canonizerData['algorithm'],
            'update_all'    => $updateAll,
            "is_disabled" =>  $this->canonizerData['is_disabled'],
            "is_one_level" => $this->canonizerData['is_one_level']
        ];


        //$appURL = env('CS_APP_URL');
        //$endpointCSStoreTree = env('CS_STORE_TREE');
        $appURL = config('app.CS_APP_URL');
        $endpointCSStoreTree = config('app.CS_STORE_TREE');

        if(empty($appURL) || empty($endpointCSStoreTree)) {
            Log::error("App url or endpoints of store tree is not defined");
            return;
        }
        $endpoint = $appURL."/".$endpointCSStoreTree;
        $headers = array('Content-Type:multipart/form-data');

        $response = Util::execute('POST', $endpoint, $headers, $requestBody);
        
        if(isset($response)) {
            $responseData = json_decode($response, true)['data'];
            $responseStatus = (bool) json_decode($response, true)['success'] === true ? 'Success' : 'Failed';
            $responseCode = json_decode($response, true)['code'] ? json_decode($response, true)['code'] : 404;

            if(isset($responseData)) {
                $responseData = json_encode($responseData[0]);
            } else {
                $responseData = null;
            }
            ProcessedJob::create([
                'payload'   => $jobPayload,
                'status'    => $responseStatus,
                'code'      => $responseCode,
                'response'  => $responseData,
                'topic_num' => $this->canonizerData['topic_num'],
            ]);
        } else {
            Log::error("Empty response, something went wrong");
        }
    }

    /**
     * Validate request data
     * @param array $data
     * @return boolean
     */
    private function validateRequestData($data) {
        
        if(!isset($data)) {
            Log::error("Empty request data");
            return false;
        }
        if(!is_array($data)) {
            Log::error("Empty request data");
            return false;
        }

        if(empty($data['topic_num'])) {
            Log::error("Empty value for topic number");
            return false;
        }

        if(empty($data['algorithm'])) {
            Log::error("Empty value for algorithm ");
            return false;
        }

        if(empty($data['asOfDate'])) {
            Log::error("Empty value for asOfDate");
            return false;
        }
        return true;
    }
}
