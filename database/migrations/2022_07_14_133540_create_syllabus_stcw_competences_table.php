<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyllabusStcwCompetencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syllabus_stcw_competences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stcw_function_id');
            $table->foreign('stcw_function_id')->references('id')->on('syllabus_stcw_functions');
            $table->text('competence_content');
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
        Schema::dropIfExists('syllabus_stcw_competences');
    }
}
