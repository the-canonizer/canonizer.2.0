<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCampColumnAttribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('camp', function (Blueprint $table) {
            //
			 $table->increments('id')->change();
			 $table->integer('topic_num')->change();
			 $table->integer('parent_camp_num')->change();
			 $table->integer('camp_num')->change();
			 $table->integer('submit_time')->change();
			 $table->integer('submitter_nick_id')->change();
             $table->integer('objector_nick_id')->change();
             $table->integer('go_live_time')->change();
             $table->integer('object_time')->change();
			 $table->string('title',80)->change();
			 $table->string('camp_name',25)->change();
			 $table->integer('camp_about_nick_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('camp', function (Blueprint $table) {
            //
        });
    }
}
