<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameStatementColumnAttribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('statement', function (Blueprint $table) {
            //
			$table->renameColumn('submitter', 'submitter_nick_id');
			$table->renameColumn('objector', 'objector_nick_id');
			$table->renameColumn('record_id', 'id');
			$table->dropColumn('statement_size');
			$table->dropColumn('statement_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('statement', function (Blueprint $table) {
            //
        });
    }
}
