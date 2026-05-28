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
        Schema::create('cus_special', function (Blueprint $table) {
            $table->id();
            $table->integer("customer_id");
            $table->integer("consignee_id");
            $table->string("special_item");
            $table->double("rate_php");
            $table->string("unit");
            $table->double("length");
            $table->double("width");
            $table->double("height");
            $table->double("cbm")->nullable();
            $table->double("kilo")->nullable();
            $table->double("value_charge")->nullable();
            $table->string("account_type")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cus_special');
    }
};
