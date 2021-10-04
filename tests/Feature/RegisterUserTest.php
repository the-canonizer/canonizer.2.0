<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterUserTest extends TestCase
{
    /**
     * A register user test 
     *
     * @return void
     */
    public function testRegisterUser()
    {
        print sprintf("Register user - %d %s", 302,PHP_EOL);
        $authCode = mt_rand(100000, 999999);
        
        $response = $this->post('/register', [
            'email' => 'unit.test2@example.com',
            'password' => 'Test@123',
            'password_confirmation' => 'Test@123',
            'first_name' =>'unit',
            'last_name' =>'test',
            'middle_name' =>'first',
            'CaptchaCode' => 'ML1234',
            'otp'=>$authCode
        ]);        
        
        $response->assertStatus(302);
    }

    public function testRegisterUserDuplicateName()
    {
        print sprintf("Register user with the same first and last name - %d %s", 302,PHP_EOL);
        $authCode = mt_rand(100000, 999999);
        $response = $this->post('/register', [
            'email' => 'unit.test21@example.com',
            'password' => 'Test@123',
            'password_confirmation' => 'Test@123',
            'first_name' =>'unit',
            'last_name' =>'test',
            'middle_name' =>'second',
            'CaptchaCode' => 'ML1234',
            'otp'=>$authCode
        ]);        
        $response->assertStatus(302);
    }
}
