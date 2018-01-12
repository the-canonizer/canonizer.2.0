<?php

// @codingStandardsIgnoreStart
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class AddTopicIdToThreads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('c_threads', function($table){
            $table->integer('topic_id');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('c_threads', function($table){
            $table->dropColumn('topic_id');
        });
    }
}
// @codingStandardsIgnoreEnd
