<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
class AddOtpToPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('person', function($table){
            $table->integer('status')->default('0');
             $table->string('otp')->nullable();
        });
        
        $this->updateExsistingUserStatus();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
    
    public function updateExsistingUserStatus(){
       return  DB::table('person')->update(array('status' => 1));
    }
}
