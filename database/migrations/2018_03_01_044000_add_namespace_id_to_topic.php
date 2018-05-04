<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddNamespaceIdToTopic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topic', function($table){
            $table->integer('namespace_id');
        });

       DB::statement("update topic t inner join namespace n on n.label = t.namespace set t.namespace_id = n.id");
       DB::statement("update topic t set t.namespace_id=1 where t.namespace_id=0");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('topic', function($table){
            $table->dropColumn('namespace_id');
        });
    }
}
