<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NickNameTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNonAuthenticatedCanNotAddNickName()
    {   
        print sprintf("Non authenticated can\'t add Nick name - To be %d %s", 302,PHP_EOL);
        $response = $this->get('/settings/nickname');
        $response->assertStatus(302);
    }

    public function testAddNickNameRequestNotOk(){
        print sprintf("Authenticated can add Nick name - To be %d %s",302,PHP_EOL);
        $user = factory(\App\User::class)->create(['type'=>'user']);

        $response = $this->actingAs($user)->post('/settings/nickname/add');
        
        $response->assertSessionHasErrors();

    }

    public function testAddNickNameRequestOk(){
        print sprintf("Authenticated can add Nick name - To be %d %s",200,PHP_EOL);
        $user = factory(\App\User::class)->create(['type'=>'user']);

        $response = $this->actingAs($user)->post('/settings/nickname/add', [
            'nick_name' => substr(str_shuffle('abcdefghipjlmnopqrstuvwwxyz'),0,5),
            'private'=>0
        ]);
        
        $response->assertSessionHas('success');

    }


}
