<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentOnboardingAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_onboarding_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('student_details');
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('course_offers');
            $table->unsignedBigInteger('academic_id');
            $table->foreign('academic_id')->references('id')->on('academic_years');
            $table->dateTime('time_in')->nullable();
            $table->string('time_in_status')->nullable();
            $table->string('time_in_remarks')->nullable();
            $table->string('time_in_process_by')->nullable();
            $table->dateTime('time_out')->nullable();
            $table->string('time_out_status')->nullable();
            $table->string('time_out_remarks')->nullable();
            $table->string('time_out_process_by')->nullable();
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
        Schema::dropIfExists('student_onboarding_attendances');
    }
}
