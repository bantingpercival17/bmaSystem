<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSemestralFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semestral_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('particular_fee_id');
            $table->foreign('particular_fee_id')->references('id')->on('particular_fees');
            $table->unsignedBigInteger('course_semestral_fee_id');
            $table->foreign('course_semestral_fee_id')->references('id')->on('course_semestral_fees');
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
        Schema::dropIfExists('semestral_fees');
    }
}
