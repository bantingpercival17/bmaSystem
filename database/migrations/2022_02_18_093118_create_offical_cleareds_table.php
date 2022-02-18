<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficalClearedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offical_cleareds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('student_details');
            $table->unsignedBigInteger('academic_id');
            $table->foreign('academic_id')->references('id')->on('academic_years');
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('course_offers');
            $table->boolean('is_cleared')->nullable();
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
        Schema::dropIfExists('offical_cleareds');
    }
}
