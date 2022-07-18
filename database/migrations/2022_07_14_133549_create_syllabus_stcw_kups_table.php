<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyllabusStcwKupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syllabus_stcw_kups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stcw_competence_id');
            $table->foreign('stcw_competence_id')->references('id')->on('syllabus_stcw_competences');
            $table->text('kup_content');
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
        Schema::dropIfExists('syllabus_stcw_kups');
    }
}
