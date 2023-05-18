<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantBriefingSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicant_briefing_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('applicant_id');
            $table->foreign('applicant_id')->references('id')->on('applicant_accounts');
            $table->date('schedule_date');
            $table->string('schedule_time');
            $table->string('category');
            $table->text('link')->nullable();
            $table->unsignedBigInteger('staff_id');
            $table->foreign('staff_id')->references('id')->on(new Expression('bma_portal.staff'));
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
        Schema::dropIfExists('applicant_briefing_schedules');
    }
}
