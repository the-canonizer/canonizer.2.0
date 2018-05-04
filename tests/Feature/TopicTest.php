<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TopicTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNonAuthenticatedUserCanNotCreateTopic()
    {
        print sprintf("Non authenticated user can't create topic - To be %d %s", 302,PHP_EOL);
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
            'topic_name' => 'Test Topic - '.substr(str_shuffle('abcdefghipjlmnopqrstuvwwxyz'),0,5),
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
}
