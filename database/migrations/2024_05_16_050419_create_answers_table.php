<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
   
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('id'); // Primary key, auto-increment
            $table->integer('quiz_id'); 
            $table->integer('question_id'); 
            $table->string('answer_name'); // Quiz name
            $table->integer('is_correct_answer')->default(0);
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
        Schema::dropIfExists('answers');
    }
}
