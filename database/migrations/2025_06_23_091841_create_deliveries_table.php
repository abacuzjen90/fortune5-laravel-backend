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
        Schema::create('sys_delivery', function (Blueprint $table) {
            $table->id();
            $table->string("branch");
            $table->string("goingto");
            $table->string("transhipment")->nullable();
            $table->date("waybilldate");
            $table->date("confirmdate")->nullable();
            $table->integer("waybillid");
            $table->string("waybillno");
            $table->string("hwaybillnumber");
            $table->string("crs_number")->nullable();
            $table->bigInteger("consignee");
            $table->bigInteger("shipper");
            $table->integer("item_id")->nullable();
            $table->bigInteger("cargoid")->nullable();
            $table->bigInteger("cargo_details_id")->nullable();
            $table->bigInteger("item_cbm_id")->nullable();
            $table->string("unit")->nullable();
            $table->string("rates")->nullable();
            $table->bigInteger("declared_value")->nullable();
            $table->bigInteger("freight_charge")->nullable();
            $table->string("item_description")->nullable();
            $table->double("item_quantity")->nullable();
            $table->double("remaining_qty")->nullable();
            $table->bigInteger("loaded")->nullable();
            $table->integer("isexceed")->nullable();
            $table->integer("exceedqty")->nullable();
            $table->date("datechange")->nullable();
            $table->text("reason")->nullable();
            $table->bigInteger("stocksinwb")->nullable();
            $table->bigInteger("isvoyage")->nullable();
            $table->bigInteger("onsave")->nullable();
            $table->string("misrouted")->nullable();
            $table->string("agency_status")->nullable();
            $table->date("posting_date")->nullable();
            $table->date("date_created")->nullable();
            $table->bigInteger("retrived_status")->nullable();
            $table->string("disposal_remarks")->nullable();
            $table->string("disposal_user")->nullable();
            $table->string("disposal_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_delivery');
    }
};
