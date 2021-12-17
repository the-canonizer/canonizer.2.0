<?php

namespace App\Jobs;

use App\Facades\Util;
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
        
        if(!$this->validateRequestData($this->canonizerData)) {
            return;
        }

        $requestBody = [
            'topic_num' => $this->canonizerData['topic_num'],
            'asofdate' => $this->canonizerData['asOfDate'],
            'algorithm' => $this->canonizerData['algorithm']
        ];

        $appURL = env('CS_APP_URL');
        $endpointCSStoreTree = env('CS_STORE_TREE');
        if(empty($appURL) || empty($endpointCSStoreTree)) {
            Log::error("App url or endpoints of store tree is not defined");
            return;
        }
        $endpoint = $appURL."/".$endpointCSStoreTree;
        $headers = array('Content-Type:multipart/form-data');

        $response = Util::execute('POST', $endpoint, $headers, $requestBody);
        
        if(isset($response)) {
            if((bool)$response['success'] === true) {
                Log::info("Success:: ".json_encode($response['data']));  
            } else {
                Log::info("Failed:: ");  
            }
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
