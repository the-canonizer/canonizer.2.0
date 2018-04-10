<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UploadFileTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testUnauthenticatedUserNotAllowed()
    {
        print sprintf("Non authenticated user can upload - To be %d %s", 302,PHP_EOL);
        $response = $this->get('/upload');
        $response->assertStatus(302);
        
    }

    public function testFileUpload(){

        print sprintf("Uploaded File should be on server %s",PHP_EOL);
        $user = factory(\App\User::class)->create(['type'=>'user']);
        $rand = rand(1000,99999);
        $response = $this->actingAs($user)->post('/upload', [
            'file' => UploadedFile::fake()->image($rand.'.jpg'),
        ]);

        $flag = File::exists(public_path('files/'.$rand.'.jpg'));
        $this->assertTrue($flag);
        
    }
}
