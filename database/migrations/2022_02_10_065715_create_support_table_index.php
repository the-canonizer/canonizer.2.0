<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportTableIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // support

        Schema::table('support', function(Blueprint $table)
        {
            $table->index('nick_name_id');
            $table->index('topic_num');
            $table->index('camp_num');
            $table->index('delegate_nick_name_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('support', function(Blueprint $table)
        {
            $table->dropIndex('support_nick_name_id_index');
            $table->dropIndex('support_topic_num_index');
            $table->dropIndex('support_camp_num_index');
            $table->dropIndex('support_delegate_nick_name_id_index');
        });
    }
}
