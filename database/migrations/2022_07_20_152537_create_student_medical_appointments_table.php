<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentMedicalAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_medical_appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('student_details');
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('course_offers');
            $table->unsignedBigInteger('academic_id');
            $table->foreign('academic_id')->references('id')->on('academic_years');
            $table->date('appointment_date');
            $table->boolean('is_approved')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->foreign('approved_by')->references('id')->on('bma_portal.staff');
            $table->boolean('is_removed')->default(false);
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
        Schema::dropIfExists('student_medical_appointments');
    }
}
