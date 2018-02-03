<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSupportInstance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::create(
            'support_instance', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('topic_support_id');
                $table->integer('camp_num');
                $table->integer('support_order');
                $table->string('submit_time');
                $table->string('status');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
