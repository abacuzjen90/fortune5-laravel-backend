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
        Schema::create('str_destination', function (Blueprint $table) {
            $table->id();
            $table->string("destination");
            $table->double("rate_cbm")->nullable();
            $table->double("rate_kilo")->nullable();
            $table->double("value_charge")->nullable();
            $table->double("minimum")->nullable();
            $table->double("advalorem")->nullable();
            $table->string("encoder");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('str_destination');
    }
};
