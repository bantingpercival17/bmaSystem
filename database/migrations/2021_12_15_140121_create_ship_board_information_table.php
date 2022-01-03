<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipBoardInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ship_board_information', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('student_details');
            $table->string('company_name');
            $table->string('vessel_name');
            $table->string('vessel_type');
            $table->string('sbt_batch');
            $table->string('shipping_company');
            $table->string('shipboard_status');
            $table->date('embarked');
            $table->date('disembarked')->nullable();
            $table->integer('number_days')->nullable();
            $table->boolean('is_removed');
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
        Schema::dropIfExists('ship_board_information');
    }
}
