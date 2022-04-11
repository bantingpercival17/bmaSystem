<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExaminationCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('examination_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('examination_id');
            $table->foreign('examination_id')->references('id')->on('examinations');
            $table->string('category_name');
            $table->string('subject_name');
            $table->text('instruction');
            $table->text('image_path')->nullable();
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
        Schema::dropIfExists('examination_categories');
    }
}
