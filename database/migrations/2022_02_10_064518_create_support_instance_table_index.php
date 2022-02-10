<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportInstanceTableIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // support_instance
        Schema::table('support_instance', function(Blueprint $table)
        {
            $table->index('topic_support_id');
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
        Schema::table('support_instance', function(Blueprint $table)
        {
            $table->dropIndex('support_instance_topic_support_id_index');
            $table->dropIndex('support_instance_camp_num_index');
        });
    }
}
