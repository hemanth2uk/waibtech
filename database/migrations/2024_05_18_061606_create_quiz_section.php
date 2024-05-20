<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizSection extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_section', function (Blueprint $table) {
            $table->increments('id'); // Primary key, auto-increment
            $table->string('quizid'); // Quiz id
            $table->string('username'); // username
            $table->integer('timetaken')->default(0);//duration
            $table->integer('score')->default(0);  //passmark
            $table->integer('is_deleted')->default(0); // Is deleted flag
            $table->timestamps(); // Created and updated dates
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quiz_section');
    }
}
