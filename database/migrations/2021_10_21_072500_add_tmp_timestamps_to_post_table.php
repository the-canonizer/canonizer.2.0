<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTmpTimestampsToPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('post', function (Blueprint $table) {
            // Create Temporary table
            $table->integer('created_at_tmp');
            $table->integer('updated_at_tmp');
        });

        DB::STATEMENT('update thread set created_at_tmp = UNIX_TIMESTAMP(created_at)');
        DB::STATEMENT('update thread set updated_at_tmp = UNIX_TIMESTAMP(updated_at)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post', function (Blueprint $table) {
            // Rollback the Migrations
            $table->dropColumn('created_at_tmp');
            $table->dropColumn('updated_at_tmp');
        });
    }
}
