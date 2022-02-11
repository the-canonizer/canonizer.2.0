<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatementTableIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('statement', function(Blueprint $table)
        {
            $table->index('topic_num');
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
        Schema::table('statement', function(Blueprint $table)
        {
            $table->dropIndex('statement_topic_num_index');
            $table->dropIndex('statement_camp_num_index');
            $table->dropIndex('statement_submitter_nick_id_index');
        });
    }
}
