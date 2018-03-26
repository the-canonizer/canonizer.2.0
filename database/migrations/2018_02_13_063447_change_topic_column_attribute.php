<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTopicColumnAttribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topic', function (Blueprint $table) {
            //
			 $table->integer('topic_num')->change();
			 $table->integer('submit_time')->change();
			 $table->integer('submitter_nick_id')->change();
             $table->integer('objector_nick_id')->change();
             $table->integer('go_live_time')->change();
             $table->integer('object_time')->change();			 
			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topic', function (Blueprint $table) {
            //
        });
    }
}
