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
        Schema::create('issuance_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("issuance_id");
            $table->foreign('issuance_id')
                    ->references('id')
                    ->on('issuance_headers')
                    ->onDelete('cascade');

            $table->integer("stock_id")->nullable();
            $table->integer("product_id")->nullable();
            $table->string("unit")->nullable();
            $table->double("quantity")->nullable();
            $table->double("unit_price")->nullable();
            $table->double("amount")->nullable();
            $table->double("discount")->nullable();
            $table->string("status")->nullable();
            $table->string("unit_type")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issuance_details');
    }
};
