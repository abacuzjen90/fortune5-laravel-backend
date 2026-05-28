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
        Schema::create('sys_aircharge', function (Blueprint $table) {
            $table->id();
            $table->string("type")->nullable();
            $table->string("consignee")->nullable();
            $table->string("wtbreak")->nullable();
            $table->double("express")->nullable();
            $table->double("perishable")->nullable();
            $table->double("gen_cargo")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_aircharge');
    }
};
