<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangeAgreeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('change_agree_logs', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('change_id')->nullable();
            $table->integer('topic_num');
            $table->integer('camp_num')->nullable();
            $table->integer('nick_name_id');
            $table->string('change_for', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('change_agree_logs');
    }
}
