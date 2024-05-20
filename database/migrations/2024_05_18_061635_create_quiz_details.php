<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_details', function (Blueprint $table) {
            $table->increments('id'); // Primary key, auto-increment
            $table->integer('section_id')->default(0);//duration
            $table->string('question_id'); // Quiz id
            $table->string('answer_id'); // username
            $table->integer('is_correct_anser')->default(0);//duration
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
        Schema::dropIfExists('quiz_details');
    }
}
