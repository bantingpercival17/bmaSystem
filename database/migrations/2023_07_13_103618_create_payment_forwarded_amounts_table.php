<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentForwardedAmountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_forwarded_amounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('previous_assessment_id');
            $table->foreign('previous_assessment_id')->references('id')->on('payment_assessments');
            $table->double('forwarded_amount');
            $table->unsignedBigInteger('current_assessment_id');
            $table->foreign('current_assessment_id')->references('id')->on('payment_assessments');
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
        Schema::dropIfExists('payment_forwarded_amounts');
    }
}
