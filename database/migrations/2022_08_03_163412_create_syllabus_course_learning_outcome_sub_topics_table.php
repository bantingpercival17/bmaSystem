<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyllabusCourseLearningOutcomeSubTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syllabus_course_learning_outcome_sub_topics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sub_topic_id');
            $table->foreign('sub_topic_id')->references('id')->on('syllabus_course_sub_topic_learning_outcomes');
            $table->string('learning_outcome_content');
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
        Schema::dropIfExists('syllabus_course_learning_outcome_sub_topics');
    }
}
