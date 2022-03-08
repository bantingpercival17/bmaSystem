<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_verifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subject_class_id');
            $table->foreign('subject_class_id')->references('id')->on('subject_classes');
            $table->boolean('is_approved')->nullable();
            $table->text('comments')->nullable();
            $table->string('approved_by')->nullable();
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
        Schema::dropIfExists('grade_verifications');
    }
}
