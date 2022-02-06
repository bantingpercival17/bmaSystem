<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentPasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_password_resets', function (Blueprint $table) {
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('student_details');
            $table->string('password_string');
            $table->string('is_status');
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
        Schema::dropIfExists('student_password_resets');
    }
}
