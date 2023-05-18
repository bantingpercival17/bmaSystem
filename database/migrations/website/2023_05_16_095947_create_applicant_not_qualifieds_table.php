<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantNotQualifiedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_not_qualifieds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_id');
            $table->foreign('applicant_id')->references('id')->on('applicant_accounts');
            $table->unsignedBigInteger('staff_id');
            $table->foreign('staff_id')->references('id')->on(new Expression('bma_portal.staff'));
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on(new Expression('bma_portal.course_offers'));
            $table->unsignedBigInteger('academic_id');
            $table->foreign('academic_id')->references('id')->on(new Expression('bma_portal.academic_years'));
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
        Schema::dropIfExists('applicant_not_qualifieds');
    }
}
