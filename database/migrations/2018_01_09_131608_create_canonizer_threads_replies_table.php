<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// @codingStandardsIgnoreStart
class CreateCanonizerThreadsRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'replies', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->integer('c_thread_id');
                $table->text('body');
                $table->timestamps();
            }
        ); 
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replies');
    }
}
// @codingStandardsIgnoreEnd