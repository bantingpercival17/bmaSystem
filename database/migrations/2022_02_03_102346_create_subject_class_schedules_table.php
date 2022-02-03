<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectClassSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_class_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_class_id');
            $table->foreign('subject_class_id')->references('id')->on('subject_classes');
            $table->string('day');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('created_by');
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
        Schema::dropIfExists('subject_class_schedules');
    }
}
