<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_submissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subject_class_id');
            $table->foreign('subject_class_id')->references('id')->on('subject_classes');
            $table->string('form')->nullable();
            $table->string('period')->nullable();
            $table->boolean('is_approved')->nullable();
            $table->text('comments')->nullable();
            /* $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('course_offers'); */
            $table->string('approved_by')->nullable();
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
        Schema::dropIfExists('grade_submissions');
    }
}
