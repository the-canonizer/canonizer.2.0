<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsActiveFieldInPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('person') && !Schema::hasColumn('person', 'is_active')) {
            Schema::table('person', function (Blueprint $table) {
                $table->tinyInteger('is_active')->default(1)->comment('0 => Inactive, 1 => Active');
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
        //
    }
}
