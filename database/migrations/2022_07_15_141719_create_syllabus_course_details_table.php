<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyllabusCourseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syllabus_course_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_syllabus_id');
            $table->foreign('course_syllabus_id')->references('id')->on('course_syllabi');
            $table->text('course_intake_limitations');
            $table->text('faculty_requirements');
            $table->text('teaching_facilities_and_equipment');
            $table->text('teaching_aids');
            $table->text('references');
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
        Schema::dropIfExists('syllabus_course_details');
    }
}
