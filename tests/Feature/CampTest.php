<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use DB;

/**
 * Test Case For Camp
 * Author @Reena_talentelgia
 *
 */
class CampTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNonAuthenticatedUserCanNotCreateCamp()
    {
        print sprintf("Non authenticated user can't create topic - To be %d %s", 302,PHP_EOL);
        $response = $this->post('/camp/save');
        $response->assertStatus(302);
    }

    public function testCanNotCreatCampWithInvaliData(){
        print sprintf("Can not create camp with invalid data \n ",302,PHP_EOL);
        $user = factory(\App\User::class)->create(['type'=>'user']);
        $response = $this->actingAs($user)->post('/camp/save',['camp_name'=>'Test Camp']);
        $response->assertSessionHasErrors();

    }

    public function testCanCreateCampWithValidData(){
        print sprintf("Create Camp with Valid data \n",200,PHP_EOL);
        $user = factory(\App\User::class)->create(['type'=>'user']);
        $response = $this->actingAs($user)->post('/settings/nickname/add', [
            'nick_name' => substr(str_shuffle('abcdefghipjlmnopqrstuvwwxyz'),0,5),
            'private'=>0
        ]);
        $encode = \App\Library\General::canon_encode($user->id);
        $nickname = \App\Model\Nickname::where('owner_code','=',$encode)->first();

        $topic = factory(\App\Model\Topic::class)->create([
            'go_live_time'=>time(),
            'submitter_nick_id'=>$nickname->id,
            'note'=>'Test note'
        ]);
        
        if($nickname){
            $topicName = 'php unit test camp '.substr(str_shuffle('abcdefghipjlmnopqrstuvwwxyz'),0,5);
            $response = $this->actingAs($user)->post('/camp/save', [
                'topic_num' => $topic->topic_num,
                'nick_name' => $nickname->id,
                'parent_camp_num' => 1,
                'camp_name' => $topicName,
                'keywords' => 'test case',
                'note' => 'fefew',
                'camp_about_url' => '',
                'camp_about_nick_id' => 0
            ]);
            $response->assertSessionHas('test_case_success');
        }else{
            $this->assertTrue(false);
        }
       
    }

    public function testCanNotCretaeCampWithLiveCampName(){

    }

    public function testCanNotCreateCampWithInReviewCampName(){

    }

    public function testCanCreateCampWithObjectedCampName(){

    }

    public function testCanCreateCampWithOldCampName(){

    }
}
