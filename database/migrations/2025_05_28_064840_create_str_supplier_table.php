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
        Schema::create('str_supplier', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name', 100);
            $table->text('address');
            $table->string('contact_details', 100)->nullable();
            $table->string('contact_person', 100)->nullable();
            $table->string('terms', 100)->nullable();
            $table->string('tin', 100)->nullable();
            $table->bigInteger('tax')->nullable();
            $table->string('emailaddress', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('str_supplier');
    }
};
