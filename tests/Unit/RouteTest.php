<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RouteTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function providerAllUrisWithResponseCode()
    {
        return [
            ['/', 200, 'Home Page'],
            ['/home', 200, 'Home Page'],
            ['/login', 200, 'Login Page'],
            ['/register', 200, 'Register Page'],
            ['/browse', 200, 'Browse topic'],
            ['/topic/95-3-Pronged-Attack-for-Thriving-Humanity/1', 200, ' Topic page']
        ];
    }
    /**
    * This is kind of a smoke test
    *
    * @dataProvider providerAllUrisWithResponseCode
    **/
    public function testApplicationUriResponses($uri, $responseCode, $visibleText)
    {
        print sprintf('%s --> checking URI : %s - to be %d  %s', $visibleText,$uri, $responseCode, PHP_EOL);
        $response = $this->call('GET', $uri);
        $this->assertEquals($responseCode, $response->status());
    
    }
}
