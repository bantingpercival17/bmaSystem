<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipboardPerformanceReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipboard_performance_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shipboard_id');
            $table->foreign('shipboard_id')->references('id')->on('ship_board_information');
            $table->string('month');
            $table->string('date_covered');
            $table->string('task_trb');
            $table->string('trb_code');
            $table->string('date_preferred');
            $table->boolean('daily_journal')->nullable();
            $table->boolean('have_signature')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('shipboard_performance_reports');
    }
}
