<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseSemestralFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_semestral_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('curriculum_id');
            $table->foreign('curriculum_id')->references('id')->on('curriculum');
            $table->unsignedBigInteger('academic_id');
            $table->foreign('academic_id')->references('id')->on('academic_years');
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('course_offers');
            $table->integer('year_level');
            $table->boolean('is_removed');
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
        Schema::dropIfExists('course_semestral_fees');
    }
}
