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
        Schema::create('stock_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("header_id");
            $table->foreign('header_id')
                    ->references('id')
                    ->on('stock_headers')
                    ->onDelete('cascade');

            $table->integer("product_id")->nullable();
            $table->string("sku")->nullable();
            $table->string("unit")->nullable();
            $table->double("quantity")->nullable();
            $table->double("issued_qty")->nullable();
            $table->double("remaining_qty")->nullable();
            $table->double("price_per_unit")->nullable();
            $table->double("cost_per_unit")->nullable();
            $table->double("discount")->nullable();
            $table->double("total_purchase_cost")->nullable();
            $table->string("common_name")->nullable();
            $table->string("big_unit")->nullable();
            $table->double("big_qty")->nullable();
            $table->double("big_price")->nullable();
            $table->double("big_cost")->nullable();
            $table->double("small_conversion")->nullable();
            $table->double("big_conversion")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_details');
    }
};
