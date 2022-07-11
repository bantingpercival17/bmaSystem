<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipboardExaminationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipboard_examinations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('student_details');
            $table->string('examination_code');
            $table->date('examination_start');
            $table->date('examination_end');
            $table->boolean('is_finish')->nullable();
            $table->integer('is_reset')->nullable();
            $table->integer('staff_id')->nullable();
            $table->foreign('staff_id')->references('id')->on('staff');
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
        Schema::dropIfExists('shipboard_examinations');
    }
}
