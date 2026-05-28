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
        Schema::create('rental_leases', function (Blueprint $table) {
            $table->id();
            $table->integer('unit_id');
            $table->integer('tenant_id');
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->double('monthly_rent')->nullable();
            $table->double('deposit')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_leases');
    }
};
