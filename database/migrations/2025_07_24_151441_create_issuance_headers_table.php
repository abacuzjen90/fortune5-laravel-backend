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
        Schema::create('issuance_headers', function (Blueprint $table) {
            $table->id();
            $table->string("drletter")->nullable();
            $table->string("drno")->nullable();
            $table->string("customer_name")->nullable();
            $table->string("address")->nullable();
            $table->string("contact_number")->nullable();
            $table->double("total_quantity")->nullable();
            $table->double("total_amount")->nullable();
            $table->date("transaction_date")->nullable();
            $table->string("terms")->nullable();
            $table->string("encoded_by")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issuance_headers');
    }
};
