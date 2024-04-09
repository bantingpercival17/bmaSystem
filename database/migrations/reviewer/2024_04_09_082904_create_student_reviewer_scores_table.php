<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentReviewerScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql3')->create('student_reviewer_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on(new Expression(env('DB_DATABASE') . '.student_details'));
            $table->unsignedBigInteger('examination_id');
            $table->foreign('examination_id')->references('id')->on(new Expression(env('DB_DATABASE') . '.examinations'));
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on(new Expression(env('DB_DATABASE') . '.examination_categories'));
            $table->integer('score');
            $table->boolean('is_removed')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_reviewer_scores');
    }
}
