<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampSubscriptionTableIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('camp_subscription', function(Blueprint $table)
        {
            $table->index('user_id');
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
        Schema::table('camp_subscription', function(Blueprint $table)
        {
            $table->dropIndex('camp_subscription_user_id_index');
            $table->dropIndex('camp_subscription_topic_num_index');
            $table->dropIndex('camp_subscription_camp_num_index');
        });
    }
}
