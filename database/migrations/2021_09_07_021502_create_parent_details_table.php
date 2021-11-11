<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parent_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('student_details');
            $table->string('father_last_name', 100);
            $table->string('father_first_name', 100);
            $table->string('father_middle_name', 100);
            $table->string('father_educational_attainment');
            $table->string('father_employment_status');
            $table->string('father_working_arrangement');
            $table->string('father_contact_number');

            $table->string('mother_last_name', 100);
            $table->string('mother_first_name', 100);
            $table->string('mother_middle_name', 100);
            $table->string('mother_educational_attainment');
            $table->string('mother_employment_status');
            $table->string('mother_working_arrangement');
            $table->string('mother_contact_number');

            $table->string('guardian_last_name', 100);
            $table->string('guardian_first_name', 100);
            $table->string('guardian_middle_name', 100);
            $table->string('guardian_educational_attainment');
            $table->string('guardian_employment_status');
            $table->string('guardian_working_arrangement');
            $table->string('guardian_contact_number');

            $table->string('household_income');
            $table->string('dswd_listahan');
            $table->string('homeownership');
            $table->string('car_ownership');

            $table->text('available_devices');
            $table->text('available_connection');
            $table->text('available_provider');
            $table->text('learning_modality');
            $table->text('distance_learning_effect');
            $table->boolean('is_removed')->nullable();
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
        Schema::dropIfExists('parent_details');
    }
}
