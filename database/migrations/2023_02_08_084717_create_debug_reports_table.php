<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebugReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debug_reports', function (Blueprint $table) {
            $table->id();
            $table->string('type_of_user');
            $table->string('user_name');
            $table->text('user_ip_address');
            $table->text('error_message');
            $table->text('url_error');
            $table->date('date_resolve')->nullable();
            $table->boolean('is_status');
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
        Schema::dropIfExists('debug_reports');
    }
}
