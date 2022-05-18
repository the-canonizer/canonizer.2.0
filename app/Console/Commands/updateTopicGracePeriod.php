<?php

namespace App\Console\Commands;

use App\Model\Topic;
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
    protected $description = 'Update grace perid value to 0 where grace period duration (1 hour) is passed';

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
        $topics = Topic::select('id', 'submit_time')->where('grace_period', '1')->where('objector_nick_id', NULL)->get();
        $currentTime = time();
        
        foreach($topics as $topic) {
            $submittedTime = $topic->submit_time;
            $gracePeriodEndTime = $submittedTime + 3600;
            if($currentTime > $gracePeriodEndTime) {
                $topic->grace_period = 0;
                $topic->update();
            }
        }
    }
}
