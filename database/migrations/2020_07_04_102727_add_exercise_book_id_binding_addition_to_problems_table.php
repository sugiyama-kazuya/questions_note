<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExerciseBookIdBindingAdditionToProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problems', function (Blueprint $table) {
            $table->dropForeign(['exercise_book_id']);
            $table->foreign('exercise_book_id')->references('id')->on('exercise_books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problems', function (Blueprint $table) {
            $table->dropForeign(['exercise_book_id']);
            $table->foreign('exercise_book_id')->references('id')->on('exercise_books');
        });
    }
}
