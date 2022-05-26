<?php

namespace App\Console\Commands;

use App\Model\ProcessedJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteProcessedJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'processJob:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove each topic duplicate entries except latest ones in processed jobs table';

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
    public function handle()
    {
        $processedJobs = DB::table('processed_jobs')->select(DB::raw('MAX(id) as latest_topic_id'))->where('topic_num', '!=', NULL)->groupBy('topic_num')->get();
        $notDelId = [];
        
        if(count($processedJobs) > 0) {
            foreach($processedJobs as $processedJob) {
                $notDelId[] = $processedJob->latest_topic_id;
            }
        }
        
        if(count($processedJobs) > 0) {
            if(count($notDelId) > 0) {
                ProcessedJob::whereNotIn('id', $notDelId)->delete();
                $this->info("Job executed successfully");
            } else {
                $this->error("No proccessed job deleted");
            }
        } else {
            $this->error("No proccessed jobs found");
        }
    }
}
