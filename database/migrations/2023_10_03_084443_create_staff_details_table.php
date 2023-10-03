<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->foreign('staff_id')->references('id')->on('staff');
            $table->string('birthday');
            $table->string('civil_status');
            $table->text('complete_address');
            $table->string('contact_number');
            $table->text('contact_person');
            $table->string('contact_person_number');
            $table->string('contact_person_relationship');
            $table->string('contact_person_address');
            $table->string('sss_number');
            $table->string('tin_number');
            $table->string('blood_type');
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
        Schema::dropIfExists('staff_details');
    }
}
