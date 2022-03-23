<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantDetialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('applicant_detials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('applicant_id');
            $table->foreign('applicant_id')->references('id')->on('applicant_accounts');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name');
            $table->string('extention_name');
            $table->date('birthday');
            $table->longText('birth_place');
            $table->string('sex');
            $table->string('nationality');
            $table->string('civil_status');
            $table->string('street');
            $table->string('barangay');
            $table->string('municipality');
            $table->string('province');
            $table->string('religion');
            $table->integer('zip_code');

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

            $table->string('elementary_school_name')->nullable();
            $table->text('elementary_school_address')->nullable();
            $table->date('elementary_school_year')->nullable();
            $table->string('junior_high_school_name')->nullable();
            $table->text('junior_high_school_address')->nullable();
            $table->date('junior_high_school_year')->nullable();
            $table->string('senior_high_school_name')->nullable();
            $table->text('senior_high_school_address')->nullable();
            $table->date('senior_high_school_year')->nullable();
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
        Schema::connection('mysql2')->dropIfExists('applicant_detials');
    }
}
