<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffPayrollDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_payroll_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payroll_id');
            $table->foreign('payroll_id')->references('id')->on('staff_payrolls');
            $table->unsignedBigInteger('salary_id');
            $table->foreign('salary_id')->references('id')->on('staff_salary_detailes');
            $table->double('absence_late', 15, 2)->nullable();
            $table->double('ot_holiday', 15, 2)->nullable();
            $table->double('teaching_load', 15, 2)->nullable();
            $table->double('teaching_overload', 15, 2)->nullable();
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
        Schema::dropIfExists('staff_payroll_details');
    }
}
