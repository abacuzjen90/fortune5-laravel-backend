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
        Schema::create('rental_spaces', function (Blueprint $table) {
            $table->id();
            $table->string('property_name');
            $table->string('unit_number');
            $table->string('type')->nullable();
            $table->string('address')->nullable();
            $table->double('monthly_rent')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_spaces');
    }
};
