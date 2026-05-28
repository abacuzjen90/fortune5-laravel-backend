<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('deleted_leave_records', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('emp_no');
            $table->string('emp_name');
            $table->string('leave_type');
            $table->date('date');
            $table->date('date_to');
            $table->double('no_of_day');
            $table->year('year_of_leave');
            $table->text('reason');
            $table->string('status');
            $table->bigInteger('encoder_id');
            $table->string('encoded_by');
            $table->dateTime('date_encoded');
            $table->string('deleted_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deleted_leave_records');
    }
};
