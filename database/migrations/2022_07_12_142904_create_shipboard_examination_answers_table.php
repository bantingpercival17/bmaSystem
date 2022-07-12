<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipboardExaminationAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipboard_examination_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('examination_id');
            $table->foreign('examination_id')->references('id')->on('shipboard_examinations');
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on('examination_questions');
            $table->unsignedBigInteger('choices_id')->nullable();
            $table->foreign('choices_id')->references('id')->on('examination_question_choices');
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
        Schema::dropIfExists('shipboard_examination_answers');
    }
}
