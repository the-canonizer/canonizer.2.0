<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadTableIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // thread

        Schema::table('thread', function(Blueprint $table)
        {
            $table->index('topic_id');
            $table->index('camp_id');
            $table->index('user_id');
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thread', function(Blueprint $table)
        {
            $table->dropIndex('thread_topic_id_index');
            $table->dropIndex('thread_camp_id_index');
            $table->dropIndex('thread_user_id_index');
            $table->dropIndex('thread_title_index');
        });
    }
}
