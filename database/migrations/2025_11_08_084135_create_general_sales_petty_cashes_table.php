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
        Schema::create('general_sales_petty_cashes', function (Blueprint $table) {
            $table->id();
            $table->string("date");
            $table->string("denomination");
            $table->integer("quantity");
            $table->double("amount");
            $table->string("encoder");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_sales_petty_cashes');
    }
};
