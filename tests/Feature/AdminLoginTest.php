<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminLoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNonAdminUserNotAllowed()
    {
        print sprintf("Non-Admin user admin acces - To be %d %s", 302 ,PHP_EOL);
        $user = factory(\App\User::class)->create(['type'=>'user']);
        $response = $this->actingAs($user)
                         ->get('/admin');
        $response->assertStatus(302);
    
    }

    public function testAdminUserCanAccess(){

        print sprintf("Admin user admin acces - To be %d %s", 200 ,PHP_EOL);
        $user = factory(\App\User::class)->create(['type'=>'admin']);
        $response = $this->actingAs($user)
                         ->get('/admin');
        $response->assertStatus(200);
    }

    public function testUnknownTypeUserNotAllowed(){
        print sprintf("Unknown user admin acces - To be %d %s", 302,PHP_EOL);
        $user = factory(\App\User::class)->create();
        $response = $this->actingAs($user)
                         ->get('/admin');
        $response->assertStatus(302);
    }
    
}
