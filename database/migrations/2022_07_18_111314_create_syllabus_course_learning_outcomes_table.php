<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyllabusCourseLearningOutcomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syllabus_course_learning_outcomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_syllabus_id');
            $table->foreign('course_syllabus_id')->references('id')->on('course_syllabi');
            $table->unsignedBigInteger('course_outcome_id');
            $table->foreign('course_outcome_id')->references('id')->on('syllabus_course_outcomes');
            $table->string('learning_outcomes');
            $table->text('term')->nullable();
            $table->integer('theoretical')->nullable();
            $table->integer('demonstration')->nullable();
            $table->text('weeks')->nullable();
            $table->text('reference')->nullable();
            $table->text('teaching_aids')->nullable();
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
        Schema::dropIfExists('syllabus_course_learning_outcomes');
    }
}
