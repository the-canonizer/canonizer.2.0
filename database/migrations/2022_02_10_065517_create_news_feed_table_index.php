<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsFeedTableIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // news_feed
        Schema::table('news_feed', function(Blueprint $table)
        {
            $table->index('topic_num');
            $table->index('camp_num');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news_feed', function(Blueprint $table)
        {
            $table->dropIndex('news_feed_topic_num_index');
            $table->dropIndex('news_feed_camp_num_index');
        });
    }
}
