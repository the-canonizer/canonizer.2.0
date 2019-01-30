<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNewsFeed extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('news_feed', function (Blueprint $table) {
            $table->increments('id');
            $table->text('display_text');
            $table->text('link');
            $table->integer('topic_num');
            $table->integer('camp_num');
            $table->integer('available_for_child')->default(0);
            $table->integer('order_id');
            $table->string('submit_time');
            $table->string('end_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }

}
