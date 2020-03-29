<?php

namespace App;

use Illuminate\Support\Facades\Mail;

use App\Mail\ForumPostSubmittedMail;
use App\Mail\ForumThreadCreatedMail;

use App\Model\Camp;
use App\Model\Nickname;
use App\Model\Support;
use App\Model\Topic;

class CommonForumFunctions
{
    /**
     * [sendEmailToSupporters description]
     * @param  [type] $topicid  [description]
     * @param  [type] $campnum  [description]
     * @param  [type] $link     [description]
     * @param  [type] $post     [description]
     * @param  [type] $threadId [description]
     * @param  [type] $nick_id  [description]
     * @return [type]           [description]
     */
    public static function sendEmailToSupportersForumPost($topicid, $campnum, $link, $post, $threadId, $nick_id, $topic_name_encoded)
    {
        $bcc_email = [];
        $subscriber_bcc_email = [];

        $camp  = CommonForumFunctions::getForumLiveCamp($topicid, $campnum);
        $subCampIds = CommonForumFunctions::getForumAllChildCamps($camp);

        $topic_name = CommonForumFunctions::getTopicName($topicid);
        $camp_name = CommonForumFunctions::getCampName($topicid, $campnum);

        $data['post'] = $post;
        $data['camp_name'] = $camp_name;
        $data['thread'] = CThread::where('id', $threadId)->latest()->get();
        $data['subject'] = $topic_name." / ".$camp_name. " / ". $data['thread'][0]->title." post submitted.";
        $data['camp_url'] = "topic/".$topicid."-".$topic_name_encoded."/".$campnum."?";
        $data['nick_name'] = CommonForumFunctions::getForumNickName($nick_id);
        foreach ($subCampIds as $camp_id) {
            $userExist = [];
            $directSupporter = CommonForumFunctions::getDirectCampSupporter($topicid, $camp_id);
            $subscribers = Camp::getCampSubscribers($topicid, $camp_id);
            foreach ($directSupporter as $supporter) {
                $user = CommonForumFunctions::getUserFromNickId($supporter->nick_name_id);
                $bcc_user_email = CommonForumFunctions::getReceiver($user->email);
                $userExist[] = $user->id;
                Mail::bcc($bcc_user_email)->send(new ForumPostSubmittedMail($user, $link, $data));
            }
            if($subscribers && count($subscribers) > 0){
               $data['subscriber'] = 1;
                foreach($subscribers as $sub){
                    if(!in_array($sub,$userExist)){
                        $userSub = \App\User::find($sub);
                        $bcc_user_email = CommonForumFunctions::getReceiver($userSub->email);
                        Mail::bcc($bcc_user_email)->send(new ForumPostSubmittedMail($userSub, $link, $data));
                    }
                }
            }
            
        }

        //Mail::bcc($bcc_email)->send(new ForumPostSubmittedMail($user, $link, $data));
        return;
    }

    /**
     * [sendEmailToSupporters description]
     * @param  [type] $topicid [description]
     * @param  [type] $campnum [description]
     * @return [type]          [description]
     */
    public static function sendEmailToSupportersForumThread($topicid, $campnum, $link, $thread_title, $nick_id, $topic_name_encoded)
    {
        $bcc_email = [];        
        $subscriber_bcc_email = [];
        $camp  = CommonForumFunctions::getForumLiveCamp($topicid, $campnum);
        $subCampIds = CommonForumFunctions::getForumAllChildCamps($camp);
        $topic_name = CommonForumFunctions::getTopicName($topicid);

        $data['camp_name'] = CommonForumFunctions::getCampName($topicid, $campnum);

        $data['nick_name'] = CommonForumFunctions::getForumNickName($nick_id);

        $data['subject'] = $topic_name." / ".$data['camp_name']. " / ". $thread_title.
                            " created";
        $data['camp_url'] = "topic/".$topicid."-".$topic_name_encoded."/". $campnum."?";

        $data['thread_title'] = $thread_title;

        foreach ($subCampIds as $camp_id) {            
            $userExist = [];
            $directSupporter = CommonForumFunctions::getDirectCampSupporter($topicid, $camp_id);
            $subscribers = Camp::getCampSubscribers($topicid, $camp_id);

            foreach ($directSupporter as $supporter) {
                $user = CommonForumFunctions::getUserFromNickId($supporter->nick_name_id);
                $bcc_user_email = CommonForumFunctions::getReceiver($user->email);
                $userExist[] = $user->id;
                //Mail::bcc($bcc_user_email)->send(new ForumThreadCreatedMail($user, $link, $data));
            }
            echo "<pre>"; print_r($userExist);
            print_r($subscribers); print_r($directSupporter); 
            if($subscribers && count($subscribers) > 0){
                    $data['subscriber'] = 1;
                    foreach($subscribers as $sub){
                        echo (!in_array($sub,$userExist));
                        echo $sub;
                        if(!in_array($sub,$userExist)){
                            $userSub = \App\User::find($sub);
                            $bcc_user_email = CommonForumFunctions::getReceiver($userSub->email);                            
                           // Mail::bcc($bcc_user_email)->send(new ForumThreadCreatedMail($userSub, $link, $data));
                        }
                    }
            }
            die;
            
        }

        // Mail::bcc($bcc_email)->send(new ForumThreadCreatedMail($user, $link, $data));
        // $data['subscriber'] = 1;
        // Mail::bcc($subscriber_bcc_email)->send(new ForumThreadCreatedMail($user, $link, $data));

        return;
    }

    /**
     * [getForumLiveCamp description]
     * @param  [type] $topic_id [description]
     * @param  [type] $camp_num [description]
     * @return [type]           [description]
     */
    public static function getForumLiveCamp($topic_id, $camp_num)
    {
        return Camp::getLiveCamp($topic_id, $camp_num);
    }

    /**
     * [getForumAllChildCamps description]
     * @param  [type] $camp [description]
     * @return [type]       [description]
     */
    public static function getForumAllChildCamps($camp)
    {
        return array_unique(Camp::getAllChildCamps($camp));
    }

    /**
     * [getForumNickName description]
     * @param  [type] $nick_id [description]
     * @return [type]          [description]
     */
    public static function getForumNickName($nick_id)
    {
        return Nickname::getNickName($nick_id);
    }

    /**
     * [getDirectCampSupporter description]
     * @param  [type] $topicid [description]
     * @param  [type] $campnum [description]
     * @return [type]          [description]
     */
    public static function getDirectCampSupporter($topicid, $campnum)
    {
        return Support::getDirectSupporter($topicid, $campnum);
    }

    /**
     * [getUserNickName description]
     * @param  [type] $nick_id [description]
     * @return [type]          [description]
     */
    public static function getUserFromNickId($nick_id)
    {
        return Nickname::getUserByNickName($nick_id);
    }

    /**
     * [getReceiver description]
     * @param  [type] $user_email [description]
     * @return [type]             [description]
     */
    public static function getReceiver($user_email)
    {
        return (config('app.env')=="production" || config('app.env')=="staging") ? $user_email : config('app.admin_email');
    }


    /**
     * [getTopicName description]
     * @param  [type] $topicid [description]
     * @return [type]          [description]
     */
    public static function getTopicName($topicid)
    {
        return Topic::where('topic_num', $topicid)->
                      orderBy('go_live_time', 'desc')->
                      first()->topic_name;
    }


    /**
     * [getCampName description]
     * @param  [type] $topicid [description]
     * @param  [type] $campnum [description]
     * @return [type]          [description]
     */
    public static function getCampName($topicid, $campnum)
    {
        return Camp::where('camp_num', $campnum)->
                     where('topic_num', $topicid)->
                     orderBy('go_live_time', 'desc')->
                     first()->camp_name;
    }
}

?>
