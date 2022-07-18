<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSyllabusStcwFunctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('syllabus_stcw_functions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stcw_reference_id');
            $table->foreign('stcw_reference_id')->references('id')->on('syllabus_stcw_references');
            $table->text('function_content');
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
        Schema::dropIfExists('syllabus_stcw_functions');
    }
}
