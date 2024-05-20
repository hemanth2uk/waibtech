<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz', function (Blueprint $table) {
            $table->increments('id'); // Primary key, auto-increment
            $table->string('quiz_name'); // Quiz name
            $table->integer('duration')->default(0);//duration
            $table->integer('passmark')->default(0);  //passmark
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
        Schema::dropIfExists('quiz');
    }
}

