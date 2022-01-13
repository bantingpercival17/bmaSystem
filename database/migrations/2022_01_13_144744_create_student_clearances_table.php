<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentClearancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_clearances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('student_details');
            $table->unsignedBigInteger('subject_class_id');
            $table->foreign('subject_class_id')->references('id')->on('subject_classes');
            $table->text('comments')->nullable();
            $table->boolean('is_approved')->nullable();
            $table->boolean('is_removed')->nullable();
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
        Schema::dropIfExists('student_clearances');
    }
}
