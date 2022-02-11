<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicSupportTableIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // topic_support
        Schema::table('topic_support', function(Blueprint $table)
        {
            $table->index('topic_num');
            $table->index('nick_name_id');
            $table->index('delegate_nick_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topic_support', function(Blueprint $table)
        {
            $table->dropIndex('topic_support_topic_num_index');
            $table->dropIndex('topic_support_nick_name_id_index');
            $table->dropIndex('topic_support_delegate_nick_id_index');
        });
    }
}
