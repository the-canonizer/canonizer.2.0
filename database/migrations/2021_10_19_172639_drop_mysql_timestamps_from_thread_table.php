<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropMysqlTimestampsFromThreadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('thread', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Reverse migration
        Schema::table('thread', function (Blueprint $table) {
            $table->timestamps();
        });

        DB::STATEMENT('update thread set created_at = FROM_UNIXTIME(created_at_tmp)');
        DB::STATEMENT('update thread set updated_at = FROM_UNIXTIME(updated_at_tmp)');
    }
}
