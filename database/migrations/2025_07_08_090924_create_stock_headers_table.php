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
        Schema::create('stock_headers', function (Blueprint $table) {
            $table->id();
            $table->string("supplier_name")->nullable();
            $table->string("delivery_receipt")->nullable();
            $table->date("order_date")->nullable();
            $table->date("delivery_date")->nullable();
            $table->string("remarks")->nullable();
            $table->string("encoded_by")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_headers');
    }
};
