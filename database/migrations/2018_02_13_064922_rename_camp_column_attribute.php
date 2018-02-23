<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameCampColumnAttribute extends Migration
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
			$table->renameColumn('submitter', 'submitter_nick_id');
			$table->renameColumn('objector', 'objector_nick_id');
			$table->renameColumn('url', 'camp_about_url');
			$table->renameColumn('nick_name_id', 'camp_about_nick_id');
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
