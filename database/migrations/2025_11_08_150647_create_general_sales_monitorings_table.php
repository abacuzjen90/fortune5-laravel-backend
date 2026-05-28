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
        Schema::create('general_sales_monitorings', function (Blueprint $table) {
            $table->id();
            $table->string("date");
            $table->string("type");
            $table->string("referenceno");
            $table->string("name");
            $table->string("description");
            $table->string("mode_of_payment");
            $table->string("gcash_referenceno")->nullable();
            $table->string("bank")->nullable();
            $table->string("bank_date")->nullable();
            $table->string("checkno")->nullable();
            $table->string("receiving_bank")->nullable();
            $table->string("bank_transfer_refno")->nullable();
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
        Schema::dropIfExists('general_sales_monitorings');
    }
};
