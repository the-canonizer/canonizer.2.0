<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldInTopicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('topic') && !Schema::hasColumn('topic', 'is_subcamp')) {
            Schema::table('topic', function (Blueprint $table) {
                $table->tinyInteger('is_disabled')->default(0)->comment('0 => Unlimted, 1 => Disabled');
                $table->tinyInteger('is_one_level')->default(0)->comment('0 => Unlimted, 1 => One level');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('topic', function (Blueprint $table) {
            //
        });
    }
}
