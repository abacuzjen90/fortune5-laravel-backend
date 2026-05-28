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
        Schema::create('sys_waybillheader', function (Blueprint $table) {
            $table->id();
            $table->string("waybillno");
            $table->string("hwaybillnumber");
            $table->string("crs_number");
            $table->string("modeoftransaction");
            $table->date("waybilldate");
            $table->string("charge_to");
            $table->string("consignee");
            $table->text("address");
            $table->string("destination_from");
            $table->string("destination_to");
            $table->string("location")->nullable();
            $table->integer("terms")->nullable();
            $table->double("customer_minimum")->nullable();
            $table->string("shipper")->nullable();
            $table->string("type")->nullable();
            $table->string("agency_type")->nullable();
            $table->string("encoder")->nullable();
            $table->date("encoded")->nullable();
            $table->string("time")->nullable();
            $table->integer("checker")->nullable();
            $table->integer("appraiser")->nullable();
            $table->string("delivered_by")->nullable();
            $table->string("pickupby")->nullable();
            $table->integer("typist_name")->nullable();
            $table->text("memo")->nullable();
            $table->string("memo_encoder")->nullable();
            $table->string("branch")->nullable();
            $table->integer("total_quantity")->nullable();
            $table->double("amount")->nullable();
            $table->string("status")->nullable();
            $table->string("shipper_own_risk")->nullable();
            $table->string("wb_missing_status")->nullable();
            $table->string("delivery")->nullable();
            $table->integer("blocklist")->nullable();
            $table->string("posting_date")->nullable();
            $table->string("posting_time")->nullable();
            $table->integer("posting_user")->nullable();
            $table->double("cancel_cost")->nullable();
            $table->text("cancel_remark")->nullable();
            $table->integer("cancel_encoder")->nullable();
            $table->integer("search_status")->nullable();
            $table->integer("receivable_id")->nullable();
            $table->string("proof_of_delivery")->nullable();
            $table->integer("transfer_status")->nullable();
            $table->integer("update_user")->nullable();
            $table->date("update_date")->nullable();
            $table->integer("retrived_status")->nullable();
            $table->string("ptf_status")->nullable();
            $table->date("ptf_date")->nullable();
            $table->string("food")->nullable();
            $table->string("liquid")->nullable();
            $table->string("breakable")->nullable();
            $table->string("glass")->nullable();
            $table->string("cm_lookup_remarks")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sys_waybillheader');
    }
};
