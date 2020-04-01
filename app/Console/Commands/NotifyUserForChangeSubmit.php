<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Model\Statement;
use App\Model\Camp;
use App\Model\Topic;
use App\Model\Support;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurposedToSupportersMail;
use App\Model\Nickname;

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
                $directSupporter = Support::getDirectSupporter($statement->topic_num, $statement->camp_num);
                $subscribers = Camp::getCampSubscribers($statement->topic_num, $statement->camp_num);
                // $link = 'statement/history/' . $id . '/' . $statement->camp_num . '?asof=bydate&asofdate=' . date('Y/m/d H:i:s', $statement->go_live_time);
                $link = 'statement/history/' . $statement->topic_num . '/' . $statement->camp_num;
                $livecamp = Camp::getLiveCamp($statement->topic_num,$statement->camp_num);
                $data['object'] = $livecamp->topic->topic_name . " / " . $livecamp->camp_name;;
                $data['go_live_time'] = $statement->go_live_time;
                $data['type'] = 'statement : for camp ';
                $data['typeobject'] = 'statement';
                $data['note'] = $statement->note;
                $nickName = Nickname::getNickName($statement->submitter_nick_id);

                $data['nick_name'] = $nickName->nick_name;
                $data['forum_link'] = 'forum/' . $statement->topic_num . '-statement/' . $statement->camp_num . '/threads';
                $data['subject'] = "Proposed change to statement for camp " . $livecamp->topic->topic_name . " / " . $livecamp->camp_name. " submitted";
                self::mailSubscribersAndSupporters($directSupporter,$subscribers,$link, $data);
                //self::mailSupporters($directSupporter, $link, $data);       //mail supporters
                echo " \n Your change to statement #' .$statement->id. 'has been submitted to your supporters \n";
            } else if ($type == 'camp') {
                $camp = $record;
                $camp->grace_period = 0;
                $camp->update();

                $directSupporter = Support::getDirectSupporter($camp->topic_num, $camp->camp_num);
                $subscribers = Camp::getCampSubscribers($camp->topic_num, $camp->camp_num);
                //$link = 'camp/history/' . $id . '/' . $camp->camp_num . '?asof=bydate&asofdate=' . date('Y/m/d H:i:s', $camp->go_live_time);
                $link = 'camp/history/' . $camp->topic_num . '/' . $camp->camp_num;
                $data['object'] = $camp->topic->topic_name . ' / ' . $camp->camp_name;
                $data['type'] = 'camp : ';
                $data['typeobject'] = 'camp';
                $data['go_live_time'] = $camp->go_live_time;
                $data['note'] = $camp->note;
                $nickName = Nickname::getNickName($camp->submitter_nick_id);

                $data['nick_name'] = $nickName->nick_name;
                $data['forum_link'] = 'forum/' . $camp->topic_num . '-' . $camp->camp_name . '/' . $camp->camp_num . '/threads';
                $data['subject'] = "Proposed change to " . $camp->topic->topic_name . ' / ' . $camp->camp_name . " submitted";

                self::mailSubscribersAndSupporters($directSupporter,$subscribers,$link, $data);
               // self::mailSupporters($directSupporter, $link, $data);         //mail supporters   
                echo "\n Your change to camp #' . $camp->id.  ' has been submitted to your supporters. \n";
            } else if ($type == 'topic') {
                $topic = $record;
                $topic->grace_period = 0;
                $topic->update();
                $directSupporter = Support::getDirectSupporter($topic->topic_num);          
                $subscribers = Camp::getCampSubscribers($camp->topic_num, 1);
                 // $link = 'topic/' . $topic->topic_num . '/' . $topic->camp_num . '?asof=bydate&asofdate=' . date('Y/m/d H:i:s', $topic->go_live_time);
                $link = 'topic-history/' . $topic->topic_num;
                $data['object'] = $topic->topic_name;
                $data['go_live_time'] = $topic->go_live_time;
                $data['type'] = 'topic : ';
                $data['typeobject'] = 'topic';
                $data['note'] = $topic->note;
                $nickName = Nickname::getNickName($topic->submitter_nick_id);

                $data['nick_name'] = $nickName->nick_name;
                $data['forum_link'] = 'forum/' . $topic->topic_num . '-' . $topic->topic_name . '/1/threads';
                $data['subject'] = "Proposed change to " . $topic->topic_name . " submitted";
                self::mailSubscribersAndSupporters($directSupporter,$subscribers,$link, $data);  
               
                //self::mailSupporters($directSupporter, $link, $data);         //mail supporters   
                echo "\n Your change to topic #'.$topic->id.' has been submitted to your supporters. \n ";
            }
        }
    }
    
    public static function mailSupporters($directSupporter, $link, $data) {
        foreach ($directSupporter as $supporter) {
            $user = Nickname::getUserByNickName($supporter->nick_name_id);
            $receiver = (config('app.env') == "production") ? $user->email : config('app.admin_email');
            Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new PurposedToSupportersMail($user, $link, $data));
        }
        return;
    }

    public static function mailSubscribersAndSupporters($directSupporter,$subscribers,$link, $dataObject){
        $alreadyMailed = [];
        $i=0;
        foreach ($directSupporter as $supporter) {
         $user = Nickname::getUserByNickName($supporter->nick_name_id);
         $alreadyMailed[] = $user->id;
         $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $user->email : config('app.admin_email');
         Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new PurposedToSupportersMail($user, $link, $dataObject));
        }
        foreach ($subscribers as $usr) {
            $userSub = \App\User::find($usr);
            if(!in_array($userSub->id, $alreadyMailed,TRUE)){
                $alreadyMailed[] = $userSub->id;
                $receiver = (config('app.env') == "production" || config('app.env') == "staging") ? $userSub->email : config('app.admin_email');
                $dataObject['subscriber'] = 1;
                Mail::to($receiver)->bcc(config('app.admin_bcc'))->send(new PurposedToSupportersMail($userSub, $link, $dataObject));
            }
            
        }
        return;
    }

}
