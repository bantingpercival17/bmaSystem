<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantExaminationAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_examination_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('examination_id');
            $table->foreign('examination_id')->references('id')->on('applicant_entrance_examinations');
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')->references('id')->on(new Expression('bma_portal.examination_questions'));
            $table->unsignedBigInteger('choices_id');
            $table->foreign('choices_id')->references('id')->on(new Expression('bma_portal.examination_question_choices'));
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
        Schema::dropIfExists('applicant_examination_answers');
    }
}
