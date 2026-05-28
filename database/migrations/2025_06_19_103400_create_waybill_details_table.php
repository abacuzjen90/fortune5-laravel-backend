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
        Schema::create('sys_waybilldetails', function (Blueprint $table) {
            $table->id("sys_wbdetailsid");
            $table->string("type")->nullable();
            $table->string("chargeto_id")->nullable();
            $table->string("waybillno");
            $table->text("wb_description");
            $table->string("unit")->nullable();
            $table->integer("quantity")->nullable();
            $table->integer("confirmqty")->nullable();
            $table->integer("remaining_qty")->nullable();
            $table->double("variance")->nullable();
            $table->double("weight")->nullable();
            $table->double("declared_value")->nullable();
            $table->double("declared_value2")->nullable();
            $table->string("value_charge")->nullable();
            $table->double("total")->nullable();
            $table->string("rates")->nullable();
            $table->double("length")->nullable();
            $table->double("width")->nullable();
            $table->double("height")->nullable();
            $table->string("kilos_or_cbm")->nullable();
            $table->double("total_kls")->nullable();
            $table->double("customer_rates")->nullable();
            $table->double("customer_cbm")->nullable();
            $table->double("customer_kilo")->nullable();
            $table->double("freight_charge")->nullable();
            $table->integer("cus_specialitem_id")->nullable();
            $table->text("cus_specialitem_remarks")->nullable();
            $table->double("line_cv")->nullable();
            $table->double("line_fc")->nullable();
            $table->integer("total_freight_charge")->nullable();
            $table->integer("cbm_uniq_id")->nullable();
            $table->text("delivery_id")->nullable();
            $table->string("delivery_status")->nullable();
            $table->integer("posting_status")->nullable();
            $table->date("created_date")->nullable();
            $table->date("modified_date")->nullable();
            $table->date("date_created")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_waybilldetails');
    }
};
