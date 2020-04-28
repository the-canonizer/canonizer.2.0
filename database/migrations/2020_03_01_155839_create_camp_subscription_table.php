<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('camp_subscription', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('topic_num');
            $table->integer('camp_num');
            $table->string('subscription_start')->nullable();
            $table->string('subscription_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('camp_subscription');
    }
}
