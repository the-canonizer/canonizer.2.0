<?php

namespace App\Console\Commands;

use App\Model\Support;
use Illuminate\Console\Command;

class FixSupport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'support:fix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix entries of delegated support';

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
        //
        $allDirectSupports = Support::where(['end'=>0,'delegate_nick_name_id'=>0])->get();
        foreach($allDirectSupports as $support){
            $this->findAndFixDelegateSupport($support);
        }
    }

    private function findAndFixDelegateSupport(Support $support)
    {
        if(!empty($support)){
            $uniqueDelegators = Support::where(['end'=>0,'delegate_nick_name_id'=>$support->nick_name_id,'topic_num'=>$support->topic_num])->select('nick_name_id')->distinct()->get();
            foreach($uniqueDelegators as $delegator){
                $start_time = $support->start;
                //Check First Delegation time
                $firstDelegation = Support::where(['end'=>0,'delegate_nick_name_id'=>$support->nick_name_id,'topic_num'=>$support->topic_num,'nick_name_id' => $delegator->nick_name_id])->orderBy('start','asc')->first();
                if(!empty($firstDelegation) && ($firstDelegation->start > $start_time)){
                    $start_time = $firstDelegation->start;
                }
                $find = [
                    'end'                   => 0,
                    'topic_num'             => $support->topic_num,
                    'camp_num'              => $support->camp_num,
                    'nick_name_id'          => $delegator->nick_name_id
                ];
                $update = [
                    'delegate_nick_name_id' => $support->nick_name_id,
                    'start'                 => $start_time,
                    'support_order'         => $support->support_order
                ];
                $updateOrCreate = Support::updateOrCreate($find,$update);
                $this->findAndFixDelegateSupport($updateOrCreate);
            }
        }
    }
}
