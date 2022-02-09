<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTrasanctionOnlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_trasanction_onlines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assessment_id');
            $table->foreign('assessment_id')->references('id')->on('payment_assessments');
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->foreign('payment_id')->references('id')->on('payment_transactions');
            $table->double('amount_paid');
            $table->string('reference_number');
            $table->string('transaction_type');
            $table->text('reciept_attach_path');
            $table->boolean('is_approved')->nullable();
            $table->text('comment_remarks')->nullable();
            $table->string('or_number')->nullable();
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
        Schema::dropIfExists('payment_trasanction_onlines');
    }
}
