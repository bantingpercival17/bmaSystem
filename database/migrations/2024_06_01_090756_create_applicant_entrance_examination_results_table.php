<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

class CreateApplicantEntranceExaminationResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('applicant_entrance_examination_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_id');
            $table->foreign('applicant_id')->references('id')->on(new Expression(env('DB_DATABASE') . '.applicant_accounts'));
            $table->unsignedBigInteger('examination_id');
            $table->foreign('examination_id')->references('id')->on('applicant_entrance_examinations');
            $table->string('score');
            $table->boolean('result');
            $table->string('remarks')->nullable();
            $table->date('examination_date');
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
        Schema::dropIfExists('applicant_entrance_examination_results');
    }
}
