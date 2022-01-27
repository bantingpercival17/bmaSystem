<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticularFeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('particular_fees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('particular_id');
            $table->foreign('particular_id')->references('id')->on('particulars');
            $table->double('particular_amount');
            $table->unsignedBigInteger('academic_id');
            $table->foreign('academic_id')->references('id')->on('academic_years');
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
        Schema::dropIfExists('particular_fees');
    }
}
