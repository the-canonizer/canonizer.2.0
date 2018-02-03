<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTopicSupport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create(
            'topic_support', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('topic_num');
                $table->integer('nick_name_id');
                $table->integer('delegate_nick_id');
                $table->string('submit_time');
				$table->string('go_live_time');
            }
        );
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
}
