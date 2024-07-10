<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComprehensiveExaminationScheduledsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comprehensive_examination_scheduleds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('student_details');
            $table->unsignedBigInteger('examinee_id');
            $table->foreign('examinee_id')->references('id')->on('comprehensive_examination_examinees');
            $table->date('scheduled');
            $table->integer('attemps')->default(3);
            $table->boolean('is_removed')->default(0);
            $table->unsignedBigInteger('scheduled_staff_id');
            $table->foreign('scheduled_staff_id')->references('id')->on('staff');
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
        Schema::dropIfExists('comprehensive_examination_scheduleds');
    }
}
