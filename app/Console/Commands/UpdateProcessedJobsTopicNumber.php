<?php

namespace App\Console\Commands;

use App\Model\ProcessedJob;
use Illuminate\Console\Command;

class UpdateProcessedJobsTopicNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processJob:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update topic number value for previous entries in processed jobs table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    // 1. Get all response  
    public function handle()
    {
        $getAllProcessedJobs = ProcessedJob::select('id', 'response')->orderBy('id', 'desc')->get();
        
        foreach($getAllProcessedJobs as $job) {
            ProcessedJob::where('id', $job->id)->update(['topic_num' => $job->response['topic_id'] ?? NULL]);
        }
    }
}
