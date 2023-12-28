<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('applicant_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('applicant_number');
            $table->string('contact_number');
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on(new Expression('bma_portal.course_offers'));
            $table->unsignedBigInteger('academic_id');
            $table->foreign('academic_id')->references('id')->on(new Expression('bma_portal.academic_years'));
            $table->text('json_details')->nullable();
            $table->text('strand')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('applicant_accounts');
    }
}
