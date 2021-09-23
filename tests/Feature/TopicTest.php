<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use DB;

/**
 * Test Case For Topic
 * Author @Reena_talentelgia
 * Total Tets Cases #7
 *
 */
class TopicTest extends TestCase
{
    
    
    public function testNonAuthenticatedUserCanNotCreateTopic()
    {
        print sprintf("Non authenticated user can't create topic - To be %d %s", 302,PHP_EOL);
        print(time());
        $response = $this->post('/topic');
        $response->assertStatus(302);
    }

    public function testCanNotCreateTopicWithInvalidData()
    {
        print sprintf("Can not create topic with invalid data  - To be %d %s",302,PHP_EOL);
        $user = factory(\App\User::class)->create(['type'=>'user']);
        $response = $this->actingAs($user)->post('/topic',['topic_name'=>'Test Topic']);
        $response->assertSessionHasErrors();
    }

    public function testCreateTopicWithAccurateData()
    {
        print sprintf("Create Topic with accurate date - To be %d %s",200,PHP_EOL);
        $user = factory(\App\User::class)->create(['type'=>'user']);

        $response = $this->actingAs($user)->post('/settings/nickname/add', [
            'nick_name' => substr(str_shuffle('abcdefghipjlmnopqrstuvwwxyz'),0,5),
            'private'=>0
        ]);

        $encode = \App\Library\General::canon_encode($user->id);
        $nickname = \App\Model\Nickname::where('owner_code','=',$encode)->first();

        if($nickname){
            $response = $this->actingAs($user)->post('/topic', [
            'topic_name' => 'Test Topic '.substr(str_shuffle('abcdefghipjlmnopqrstuvwwxyz'),0,5),
            'namespace'=>1,
            'nick_name'=>$nickname->id,
            'language'=>'English',
            'note'=>'Test Note',
            
            ]);
            $response->assertSessionHas('success');
        }
        else{
            $this->assertTrue(false);
        }        
    }

    /**
     * Test Case should consider live topics
     * And should not created topic with same name
     */
    public function testDoNoCreateTopicIfLiveTopicNameGiven(){
        $user = factory(\App\User::class)->create(['type'=>'user']);
        $nickname = $this->createFakeNickName($user);
        $liveTopic = \App\Model\Topic::where('objector_nick_id', '=', NULL)
                        ->where('go_live_time', '<=', time())
                        ->latest('submit_time')->first();

        $response = $this->actingAs($user)->post('/topic', [
            'topic_name' => $liveTopic->topic_name,
            'namespace'=>1,
            'nick_name'=>$nickname->id,
            'language'=>'English',
            'note'=>'Test Note',            
            ]);
        $errors = session('errors');
        $response->assertSessionHasErrors();
        $this->assertEquals($errors->get('topic_name')[0],"The topic name has already been taken");
    }

    /**
     * Test case should consider IN-Review Topics
     * And topic should not be created with same name
     */
    public function testDoNoCreateTopicIfIfInReviewTopicNameGiven(){
        $user = factory(\App\User::class)->create(['type'=>'user']);
        $nickname = $this->createFakeNickName($user);
        $inReviewTopic =  \App\Model\Topic::select('topic.*')
                            ->join('camp','camp.topic_num','=','topic.topic_num')
                            ->where('camp.camp_name','=','Agreement')
                            ->where('topic.objector_nick_id',"=",null)
                            ->where('topic.go_live_time',">",time())
                            ->where('topic.grace_period',"=",1)
                            ->first();
        if(!empty($inReviewTopic)){
            $errors = session('errors');
            $response->assertSessionHasErrors();
            $this->assertEquals($errors->get('topic_name')[0],"The topic name has already been taken");

        }else{
            $this->assertTrue(true);
        }
        
    }

    /**
     * Test case to ignore old topic names
     */
    public function testCreateTopicIfOldTopicNameGiven(){
        $user = factory(\App\User::class)->create(['type'=>'user']);
        $nickname = $this->createFakeNickName($user);
        $sql ="select * from topic as t1
        join
        (select max(go_live_time) as go_live_time,topic_name,topic_num
        from topic where go_live_time < '".time()."' AND objector_nick_id IS NULL GROUP by topic_num )t2
        on t1.topic_num=t2.topic_num
        where 
        t1.topic_name != t2.topic_name AND t1.go_live_time < t2.go_live_time AND 
         t1.objector_nick_id is NULL ORDER by RAND() limit 1";

        $oldTopic = DB::select(DB::raw($sql));
        if(!empty($objected)){
            $response = $this->actingAs($user)->post('/topic', [
                'topic_name' => $oldTopic[0]->topic_name,
                'namespace'=>1,
                'nick_name'=>$nickname->id,
                'language'=>'English',
                'note'=>'Test Note',
                
            ]);
            $errors = session('errors');
            if(isset($errors) && !empty($errors)){                
                if($errors->get('topic_name')[0]==="Topic name must only contain space and alphanumeric characters")
                $this->assertTrue(true);
            }else
            $response->assertSessionHas('success');

        }else{
            $this->assertTrue(true);
        }
    }
    /**
     * Test Case to ignore objected topic name
     */
    public function testCreateIfTopicObjectedTopicNameGiven(){
        $user = factory(\App\User::class)->create(['type'=>'user']);
        $nickname = $this->createFakeNickName($user);
        
       $objected = DB::select(DB::raw("select * from topic where topic_name NOT IN (SELECT topic_name from topic where go_live_time < '".time()."' AND objector_nick_id IS NULL) AND objector_nick_id IS NOT NULL ORDER BY RAND() DESC limit 1"));
       if(!empty($objected)){
            $response = $this->actingAs($user)->post('/topic', [
                'topic_name' => $objected[0]->topic_name,
                'namespace'=>1,
                'nick_name'=>$nickname->id,
                'language'=>'English',
                'note'=>'Test Note',
                
            ]);
            $errors = session('errors');
            if(isset($errors) && !empty($errors)){                
                if($errors->get('topic_name')[0]==="Topic name must only contain space and alphanumeric characters")
                $this->assertTrue(true);
            }else
            $response->assertSessionHas('success');

        }else{
            $this->assertTrue(true);
        }
    }

    public function createFakeNickName($user){
        $response = $this->actingAs($user)->post('/settings/nickname/add', [
            'nick_name' => substr(str_shuffle('abcdefghipjlmnopqrstuvwwxyz'),0,5),
            'private'=>0
        ]);
        $encode = \App\Library\General::canon_encode($user->id);
        $nickname = \App\Model\Nickname::where('owner_code','=',$encode)->first();
        return $nickname;
    }
}
