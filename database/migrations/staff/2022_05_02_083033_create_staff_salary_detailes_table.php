<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffSalaryDetailesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_salary_detailes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id');
            $table->foreign('staff_id')->references('id')->on(new Expression('bma_portal.staff'));
            $table->double('salary_amount', 15, 2);
            $table->double('allowance_amount', 15, 2);
            $table->double('sss_contribution', 15, 2)->nullable();
            $table->double('philhealth_contribution', 15, 2)->nullable();
            $table->double('pagibig_contribution', 15, 2)->nullable();
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on(new Expression('bma_portal.staff'));
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
        Schema::dropIfExists('staff_salary_detailes');
    }
}
