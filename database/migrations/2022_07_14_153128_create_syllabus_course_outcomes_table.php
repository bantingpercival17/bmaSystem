<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyllabusCourseOutcomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syllabus_course_outcomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_syllabus_id');
            $table->foreign('course_syllabus_id')->references('id')->on('course_syllabi');
            $table->string('course_outcome');
            $table->string('program_outcome');
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
        Schema::dropIfExists('syllabus_course_outcomes');
    }
}
