<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameMysqlTmpTimestampsToThreadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('thread', function (Blueprint $table) {
            //
            $table->renameColumn('created_at_tmp', 'created_at');
            $table->renameColumn('updated_at_tmp', 'updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thread', function (Blueprint $table) {
            // Rollback migration
            $table->renameColumn('created_at', 'created_at_tmp');
            $table->renameColumn('updated_at', 'updated_at_tmp');
        });
    }
}
