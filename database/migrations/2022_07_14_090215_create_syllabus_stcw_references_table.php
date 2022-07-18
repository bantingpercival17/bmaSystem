<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyllabusStcwReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syllabus_stcw_references', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_syllabus_id');
            $table->foreign('course_syllabus_id')->references('id')->on('course_syllabi');
            $table->text('stcw_table');
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
        Schema::dropIfExists('syllabus_stcw_references');
    }
}
