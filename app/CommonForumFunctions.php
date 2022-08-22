<?php

namespace App;

use App\Mail\ForumPostSubmittedMail;
use App\Mail\ForumThreadCreatedMail;
use App\Model\Camp;
use App\Model\Nickname;
use App\Model\Support;
use App\Model\Topic;
use DB;
use Illuminate\Support\Facades\Mail;

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
    public static function sendEmailToSupportersForumPost($topicid, $campnum, $link, $post, $threadId, $nick_id, $topic_name_encoded, $reply_id)
    {
        $bcc_email = [];
        $subscriber_bcc_email = [];
        $userExist = [];
        $bcc_user = [];
        $supporter_and_subscriber = [];
        $sub_bcc_user = [];
        $support_list = [];
        $subscribe_list = [];
        $camp = CommonForumFunctions::getForumLiveCamp($topicid, $campnum);
        $subCampIds = CommonForumFunctions::getForumAllChildCamps($camp);

        $topic_name = CommonForumFunctions::getTopicName($topicid);
        $camp_name = CommonForumFunctions::getCampName($topicid, $campnum);
        $post_msg = " submitted.";
        $data['post_type'] = " has made";
        if ($reply_id != "") {
            $post_msg = " updated.";
            $data['post_type'] = " has updated";
        }
        $data['post'] = $post;
        $data['camp_name'] = $camp_name;
        $data['thread'] = CThread::where('id', $threadId)->latest()->get();
        $data['subject'] = $topic_name . " / " . $camp_name . " / " . $data['thread'][0]->title . " post " . $post_msg;
        //1081 issue
        $data['namespace_id'] = CommonForumFunctions::getNamespaceId($topicid);
        $data['nick_name_id'] = $nick_id;

        $data['camp_url'] = "topic/" . $topicid . "-" . $topic_name_encoded . "/" . $campnum . "?";
        $data['nick_name'] = CommonForumFunctions::getForumNickName($nick_id);
        foreach ($subCampIds as $camp_id) {
            $directSupporter = CommonForumFunctions::getDirectCampSupporter($topicid, $camp_id);
            $subscribers = Camp::getCampSubscribers($topicid, $camp_id);
            foreach ($directSupporter as $supporter) {
                $user = CommonForumFunctions::getUserFromNickId($supporter->nick_name_id);
                $topic = \App\Model\Topic::where('topic_num', '=', $topicid)->latest('submit_time')->get();
                $topic_name_space_id = isset($topic[0]) ? $topic[0]->namespace_id : 1;
                $nickName = \App\Model\Nickname::find($supporter->nick_name_id);
                $supported_camp = $nickName->getSupportCampList($topic_name_space_id, ['nofilter' => true]);
                $supported_camp_list = $nickName->getSupportCampListNamesEmail($supported_camp, $topicid, $campnum);
                $support_list[$user->id] = $supported_camp_list;
                $ifalsoSubscriber = Camp::checkifSubscriber($subscribers, $user);
                if ($ifalsoSubscriber) {
                    $support_list_data = Camp::getSubscriptionList($user->id, $topicid, $campnum);
                    $supporter_and_subscriber[$user->id] = ['also_subscriber' => 1, 'sub_support_list' => $support_list_data];
                }
                $userExist[] = $user->id;
                $bcc_user[] = $user;
            }
            if ($subscribers && count($subscribers) > 0) {
                foreach ($subscribers as $sub) {
                    if (!in_array($sub, $userExist, true)) {
                        $userSub = \App\User::find($sub);
                        $subscriptions_list = Camp::getSubscriptionList($userSub->id, $topicid, $campnum);
                        $subscribe_list[$userSub->id] = $subscriptions_list;
                        $sub_bcc_user[] = $userSub;
                    }
                }
            }
        }
        $filtered_bcc_user = array_unique($bcc_user);
        $filtered_sub_user = array_unique(array_filter($sub_bcc_user, function ($e) use ($userExist) {
            return !in_array($e->id, $userExist);
        }));

        if (isset($filtered_bcc_user) && count($filtered_bcc_user) > 0) {
            foreach ($filtered_bcc_user as $user) {
                $bcc_email = CommonForumFunctions::getReceiver($user->email);
                $data['support_list'] = $support_list[$user->id];
                if (isset($supporter_and_subscriber[$user->id]) && isset($supporter_and_subscriber[$user->id]['also_subscriber']) && $supporter_and_subscriber[$user->id]['also_subscriber']) {
                    $data['also_subscriber'] = $supporter_and_subscriber[$user->id]['also_subscriber'];
                    $data['sub_support_list'] = $supporter_and_subscriber[$user->id]['sub_support_list'];
                }else {
                    $data['also_subscriber'] = 0;
                    $data['sub_support_list'] = [];
                }

                CommonForumFunctions::sendEmailToUserWithPrivateCheck($bcc_email, $user, $link, $data, false);
                //Mail::bcc($bcc_email)->send(new ForumPostSubmittedMail($user, $link, $data));
            }

        }

        if (isset($filtered_sub_user) && count($filtered_sub_user) > 0) {
            $data['subscriber'] = 1;
            foreach ($filtered_sub_user as $userSub) {
                $subscriber_bcc_email = CommonForumFunctions::getReceiver($userSub->email);
                $data['support_list'] = $subscribe_list[$userSub->id];

                CommonForumFunctions::sendEmailToUserWithPrivateCheck($subscriber_bcc_email, $userSub, $link, $data, false);
                //Mail::bcc($subscriber_bcc_email)->send(new ForumPostSubmittedMail($userSub, $link, $data));
            }
        }

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
        $bcc_user = [];
        $sub_bcc_user = [];
        $userExist = [];
        $camp = CommonForumFunctions::getForumLiveCamp($topicid, $campnum);
        $subCampIds = CommonForumFunctions::getForumAllChildCamps($camp);
        $topic_name = CommonForumFunctions::getTopicName($topicid);

        $data['camp_name'] = CommonForumFunctions::getCampName($topicid, $campnum);

        $data['nick_name'] = CommonForumFunctions::getForumNickName($nick_id);

        $data['subject'] = $topic_name . " / " . $data['camp_name'] . " / " . $thread_title .
            " created";
        //1081 issue
        $data['namespace_id'] = CommonForumFunctions::getNamespaceId($topicid);
        $data['nick_name_id'] = $nick_id;

        $data['camp_url'] = "topic/" . $topicid . "-" . $topic_name_encoded . "/" . $campnum . "?";

        $data['thread_title'] = $thread_title;

        foreach ($subCampIds as $camp_id) {
            $directSupporter = CommonForumFunctions::getDirectCampSupporter($topicid, $camp_id);
            $subscribers = Camp::getCampSubscribers($topicid, $camp_id);
            $i = 0;
            foreach ($directSupporter as $supporter) {
                $user = CommonForumFunctions::getUserFromNickId($supporter->nick_name_id);
                $topic = \App\Model\Topic::where('topic_num', '=', $topicid)->latest('submit_time')->get();
                $topic_name_space_id = isset($topic[0]) ? $topic[0]->namespace_id : 1;
                $nickName = \App\Model\Nickname::find($supporter->nick_name_id);
                $supported_camp = $nickName->getSupportCampList($topic_name_space_id, ['nofilter' => true]);
                $supported_camp_list = $nickName->getSupportCampListNamesEmail($supported_camp, $topicid, $campnum);
                $support_list[$user->id] = $supported_camp_list;
                $ifalsoSubscriber = Camp::checkifSubscriber($subscribers, $user);
                if ($ifalsoSubscriber) {
                    $support_list_data = Camp::getSubscriptionList($user->id, $topicid, $campnum);
                    $supporter_and_subscriber[$user->id] = ['also_subscriber' => 1, 'sub_support_list' => $support_list_data];
                }
                $bcc_user[] = $user;
                $userExist[] = $user->id;
            }
            if ($subscribers && count($subscribers) > 0) {
                foreach ($subscribers as $sub) {
                    if (!in_array($sub, $userExist, true)) {
                        $userSub = \App\User::find($sub);
                        $subscriptions_list = Camp::getSubscriptionList($userSub->id, $topicid, $campnum);
                        $subscribe_list[$userSub->id] = $subscriptions_list;
                        $sub_bcc_user[] = $userSub;
                    }
                }
            }
        }
        $filtered_bcc_user = array_unique($bcc_user);
        $filtered_sub_user = array_unique(array_filter($sub_bcc_user, function ($e) use ($userExist) {
            return !in_array($e->id, $userExist);
        }));

        if (isset($filtered_bcc_user) && count($filtered_bcc_user) > 0) {

            foreach ($filtered_bcc_user as $user) {
                $bcc_email = CommonForumFunctions::getReceiver($user->email);

                $data['support_list'] = $support_list[$user->id];
                if (isset($supporter_and_subscriber[$user->id]) && isset($supporter_and_subscriber[$user->id]['also_subscriber']) && $supporter_and_subscriber[$user->id]['also_subscriber']) {
                    $data['also_subscriber'] = $supporter_and_subscriber[$user->id]['also_subscriber'];
                    $data['sub_support_list'] = $supporter_and_subscriber[$user->id]['sub_support_list'];
                }else {
                    $data['also_subscriber'] = 0;
                    $data['sub_support_list'] = [];
                }

                CommonForumFunctions::sendEmailToUserWithPrivateCheck($bcc_email, $user, $link, $data, true);
            }
        }

        if (isset($filtered_sub_user) && count($filtered_sub_user) > 0) {
            $data['subscriber'] = 1;
            foreach ($filtered_sub_user as $userSub) {
                $subscriber_bcc_email = CommonForumFunctions::getReceiver($userSub->email);
                $data['support_list'] = $subscribe_list[$userSub->id];
                CommonForumFunctions::sendEmailToUserWithPrivateCheck($subscriber_bcc_email, $userSub, $link, $data, true);
            }
        }
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
        return (config('app.env') == "production" || config('app.env') == "staging") ? $user_email : config('app.admin_email');
    }

    /**
     * [getTopicName description]
     * @param  [type] $topicid [description]
     * @return [type]          [description]
     */
    public static function getTopicName($topicid)
    {
        return Topic::where('topic_num', $topicid)->
            where('objector_nick_id', '=', null)
            ->where('go_live_time', '<=', time())
            ->latest('submit_time')->first()->topic_name;
        // ->orderBy('go_live_time', 'desc')->
        // first()->topic_name;
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
            where('objector_nick_id', '=', null)->
            where('topic_num', $topicid)
            ->where('go_live_time', '<=', time())
            ->latest('submit_time')->first()->camp_name;
        // ->orderBy('go_live_time', 'desc')->
        // first()->camp_name;
    }

    /**
     * send email to user if email flag set then place email in bcc otherwise place email in to attribute
     * @param  string $email
     * @param  array  $user
     * @param  string $link
     * @param  array  $data
     * @param  boolean $isThread
     * 
     * @return void
     */
    public static function sendEmailToUserWithPrivateCheck($email, $user, $link, $data, $isThread = true)
    {
        if ($isThread) {
            $mailerObj = new ForumThreadCreatedMail($user, $link, $data);
        } else {
            $mailerObj = new ForumPostSubmittedMail($user, $link, $data);
        }
        
        Mail::to($email)->send($mailerObj);
    }

    /**
     * [getNamespaceId description]
     * @param  [type] $topicid [description]
     * @return [type]          [description]
    */
    public static function getNamespaceId($topicid)
    {
        return Topic::where('topic_num', $topicid)->
            where('objector_nick_id', '=', null)
            ->where('go_live_time', '<=', time())
            ->latest('submit_time')->first()->namespace_id;
        // ->orderBy('go_live_time', 'desc')->
        // first()->topic_name;
    }
}
