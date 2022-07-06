<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentAdditionalTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_additional_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('enrollment_id');
            $table->foreign('enrollment_id')->references('id')->on('enrollment_assessments');
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->foreign('payment_id')->references('id')->on('payment_transactions');
            $table->double('amount_paid');
            $table->string('reference_number');
            $table->string('transaction_type');
            $table->text('reciept_attach_path');
            $table->boolean('is_approved')->nullable();
            $table->text('comment_remarks')->nullable();
            $table->string('or_number')->nullable();
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
        Schema::dropIfExists('payment_additional_transactions');
    }
}
