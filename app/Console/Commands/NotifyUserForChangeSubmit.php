<?php

namespace App\Console\Commands;

use DB;
use App\User;
use App\Facades\Util;
use App\Model\Camp;
use App\Model\Topic;
use App\Model\Support;
use App\Model\Nickname;
use App\Model\Statement;
use App\Jobs\CanonizerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurposedToSupportersMail;

class NotifyUserForChangeSubmit extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:supporters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cron job will notify user about the change submit on topic,camp,statement once grace period is up';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $statementChange = Statement::where('grace_period', '=', 1)->get();
        if (!empty($statementChange)) {
            self::notifyChange($statementChange, 'statement');
        }
        $campChange = Camp::where('grace_period', '=', 1)->get();
        if (!empty($campChange)) {
            self::notifyChange($campChange, 'camp');
        }
        $topicChange = Topic::where('grace_period', '=', 1)->get();
        if (!empty($topicChange)) {
            self::notifyChange($topicChange, 'topic');
        }
        
        echo "command executed successfully \n";
    }

    public static function notifyChange($records, $type) {
        foreach ($records as $record) {
            if ($type == 'statement') {
                $statement = $record;
                $statement->grace_period = 0;
                $statement->update();
                $directSupporter = Support::getAllDirectSupporters($statement->topic_num, $statement->camp_num);
                $subscribers = Camp::getCampSubscribers($statement->topic_num, $statement->camp_num);
                $link = 'statement/history/' . $statement->topic_num . '/' . $statement->camp_num;
                $livecamp = Camp::getLiveCamp($statement->topic_num,$statement->camp_num);
                $data['object'] = $livecamp->topic->topic_name . " / " . $livecamp->camp_name;
                $data['support_camp'] = $livecamp->camp_name;
                $data['go_live_time'] = $statement->go_live_time;
                $data['type'] = 'statement : for camp ';
                $data['typeobject'] = 'statement';
                $data['note'] = $statement->note;
                $data['camp_num'] = $statement->camp_num;
                $nickName = Nickname::getNickName($statement->submitter_nick_id);
                $data['topic_num'] = $statement->topic_num;
                $data['nick_name'] = $nickName->nick_name;
                $data['forum_link'] = 'forum/' . $statement->topic_num . '-statement/' . $statement->camp_num . '/threads';
                $data['subject'] = "Proposed change to statement for camp " . $livecamp->topic->topic_name . " / " . $livecamp->camp_name. " submitted";
                
                self::mailSubscribersAndSupporters($directSupporter,$subscribers,$link, $data);
                echo " \n Your change to statement #' .$statement->id. 'has been submitted to your supporters \n";
            } else if ($type == 'camp') {
                $camp = $record;
                $camp->grace_period = 0;
                $camp->update();
                $directSupporter = Support::getAllDirectSupporters($camp->topic_num, $camp->camp_num);
                $subscribers = Camp::getCampSubscribers($camp->topic_num, $camp->camp_num);
                $livecamp = Camp::getLiveCamp($camp->topic_num,$camp->camp_num);
                $link = 'camp/history/' . $livecamp->topic_num . '/' . $livecamp->camp_num;
                $data['object'] = $livecamp->topic->topic_name . ' / ' . $livecamp->camp_name;
                $data['support_camp'] = $camp->camp_name;
                $data['type'] = 'camp : ';
                $data['typeobject'] = 'camp';
                $data['go_live_time'] = $camp->go_live_time;
                $data['note'] = $camp->note;
                $data['camp_num'] = $camp->camp_num;
                $nickName = Nickname::getNickName($camp->submitter_nick_id);
                $data['topic_num'] = $camp->topic_num;
                $data['nick_name'] = $nickName->nick_name;
                $data['forum_link'] = 'forum/' . $livecamp->topic_num . '-' . $livecamp->camp_name . '/' . $livecamp->camp_num . '/threads';
                $data['subject'] = "Proposed change to " . $livecamp->topic->topic_name . ' / ' . $livecamp->camp_name . " submitted";
                
                $topic = $camp->topic;
                // Dispatch Job
                if(isset($topic)) {
                    Util::dispatchJob($topic, 1, 1);
                }
                
                self::mailSubscribersAndSupporters($directSupporter,$subscribers,$link, $data);   
                echo "\n Your change to camp #' . $camp->id.  ' has been submitted to your supporters. \n";
            } else if ($type == 'topic') {
                $topicData = $record;
                $topicData->grace_period = 0;
                $topicData->update();
                $topic = Topic::getLiveTopic($topicData->topic_num);
                $directSupporter = Support::getAllDirectSupporters($topic->topic_num);          
                $subscribers = Camp::getCampSubscribers($topic->topic_num, 1);
                $link = 'topic-history/' . $topic->topic_num;
                $data['object'] = $topic->topic_name;
                $data['support_camp'] = $topic->topic_name;
                $data['go_live_time'] = $topic->go_live_time;
                $data['type'] = 'topic : ';
                $data['typeobject'] = 'topic';
                $data['note'] = $topic->note;
                $data['camp_num'] = 1;
                $nickName = Nickname::getNickName($topicData->submitter_nick_id);
                $data['topic_num'] = $topic->topic_num;
                $data['nick_name'] = $nickName->nick_name;
                $data['forum_link'] = 'forum/' . $topic->topic_num . '-' . $topic->topic_name . '/1/threads';
                $data['subject'] = "Proposed change to topic " . $topic->topic_name . " submitted";
                
                // Dispatch Job
                if(isset($topicData)) {
                    Util::dispatchJob($topicData, 1, 1);
                }
                
                self::mailSupporters($directSupporter, $link, $data);         //mail supporters  
                self::mailSubscribersAndSupporters($directSupporter,$subscribers,$link, $data);                 
                echo "\n Your change to topic #'.$topic->id.' has been submitted to your supporters. \n ";
            }
        }
    }
    
    public static function mailSupporters($directSupporter, $link, $data) {
       foreach ($directSupporter as $supporter) {
            $user = Nickname::getUserByNickName($supporter->nick_name_id);
            $topic = \App\Model\Topic::where('topic_num','=',$data['topic_num'])->latest('submit_time')->get();
            $topic_name_space_id = isset($topic[0]) ? $topic[0]->namespace_id:1;
            $nickName = \App\Model\Nickname::find($supporter->nick_name_id);
            $supported_camp = $nickName->getSupportCampList($topic_name_space_id,['nofilter'=>true]);
            $supported_camp_list = $nickName->getSupportCampListNamesEmail($supported_camp,$data['topic_num'],$data['camp_num']);
            $data['support_list'] = $supported_camp_list; 
            $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
            try{

            Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new PurposedToSupportersMail($user, $link, $data));
            }catch(\Swift_TransportException $e){
                        throw new \Swift_TransportException($e);
                    } 
        }
        return;
    }

     private static function mailSubscribersAndSupporters($directSupporter,$subscribers,$link, $dataObject){
        $alreadyMailed = [];
        $i=0;
        foreach ($directSupporter as $supporter) {            
        $supportData = $dataObject;
         $user = Nickname::getUserByNickName($supporter->nick_name_id);
         $alreadyMailed[] = $user->id;
         $topic = \App\Model\Topic::where('topic_num','=',$supportData['topic_num'])->latest('submit_time')->get();
         $topic_name_space_id = isset($topic[0]) ? $topic[0]->namespace_id:1;
         $nickName = \App\Model\Nickname::find($supporter->nick_name_id);
         $supported_camp = $nickName->getSupportCampList($topic_name_space_id,['nofilter'=>true]);
         $supported_camp_list = $nickName->getSupportCampListNamesEmail($supported_camp,$supportData['topic_num'],$supportData['camp_num']);
         $supportData['support_list'] = $supported_camp_list; 
          $ifalsoSubscriber = Camp::checkifSubscriber($subscribers,$user);
          if($ifalsoSubscriber){
            $supportData['also_subscriber'] = 1;
            $supportData['sub_support_list'] = Camp::getSubscriptionList($user->id,$supportData['topic_num'],$supportData['camp_num']);      
         }
         
         $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
         try{

         Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new PurposedToSupportersMail($user, $link, $supportData));
         }catch(\Swift_TransportException $e){
                        throw new \Swift_TransportException($e);
                    } 
        }

        foreach ($subscribers as $usr) {            
            $subscriberData = $dataObject;
            $userSub = \App\User::find($usr);
            if(!in_array($userSub->id, $alreadyMailed,TRUE)){
                $alreadyMailed[] = $userSub->id;
                $subscriptions_list = Camp::getSubscriptionList($userSub->id,$subscriberData['topic_num'],$subscriberData['camp_num']);
                $subscriberData['support_list'] = $subscriptions_list; 
                $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $userSub->email : config('app.admin_email');
                $subscriberData['subscriber'] = 1;
                try{

              Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new PurposedToSupportersMail($userSub, $link, $subscriberData));
                }catch(\Swift_TransportException $e){
                        throw new \Swift_TransportException($e);
                    } 
            }
            
        }
        return;
    }

}
