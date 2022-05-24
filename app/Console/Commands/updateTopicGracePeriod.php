<?php

namespace App\Console\Commands;

use App\Model\Topic;
use App\Model\Camp;
use App\Model\Statement;
use Illuminate\Console\Command;

class updateTopicGracePeriod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gracePeriod:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update grace perid value to zero(0) where grace period duration (1 hour) is completed for topics, camps and statements';

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
        $currentTime = time();

        /**
         * Update each topic grace period where grace period duration is completed
         */
        $topics = Topic::select('id', 'submit_time')->where('grace_period', '1')->where('objector_nick_id', NULL)->get();
        
        if($topics->count() > 0) {
            foreach($topics as $topic) {
                $submittedTime = $topic->submit_time;
                $gracePeriodEndTime = $submittedTime + 3600;
                if($currentTime > $gracePeriodEndTime) {
                    $topic->grace_period = 0;
                    $topic->update();
                }
            }
        }
        

        /**
         * Update each camp grace period where grace period duration is completed
         */
        $camps = Camp::select('id', 'submit_time')->where('grace_period', '1')->where('objector_nick_id', NULL)->get();
        
        if($camps->count() > 0) {
            foreach($camps as $camp) {
                $submittedTime = $camp->submit_time;
                $gracePeriodEndTime = $submittedTime + 3600;
                if($currentTime > $gracePeriodEndTime) {
                    $camp->grace_period = 0;
                    $camp->update();
                }
            }
        }

        /**
         * Update each statement grace period where grace period duration is completed
         */
        $statements = Statement::select('id', 'submit_time')->where('grace_period', '1')->where('objector_nick_id', NULL)->get();
        
        if($statements->count() > 0) {
            foreach($statements as $statement) {
                $submittedTime = $statement->submit_time;
                $gracePeriodEndTime = $submittedTime + 3600;
                if($currentTime > $gracePeriodEndTime) {
                    $statement->grace_period = 0;
                    $statement->update();
                }
            }
        }
    }
}
