<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampTableIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('camp', function(Blueprint $table)
        {
            $table->index('topic_num');
            $table->index('parent_camp_num');
            $table->index('camp_num');
            $table->index('submitter_nick_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('camp', function(Blueprint $table)
        {
            $table->dropIndex('camp_topic_num_index');
            $table->dropIndex('camp_parent_camp_num_index');
            $table->dropIndex('camp_camp_num_index');
            $table->dropIndex('camp_submitter_nick_id_index');
        });
    }
}
