<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicTableIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // topic
        Schema::table('topic', function(Blueprint $table)
        {
            $table->index('topic_num');
            $table->index('submitter_nick_id');
            $table->index('objector_nick_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topic', function(Blueprint $table)
        {
            $table->dropIndex('topic_topic_num_index');
            $table->dropIndex('topic_submitter_nick_id_index');
            $table->dropIndex('topic_objector_nick_id_index');
        });
    }
}
