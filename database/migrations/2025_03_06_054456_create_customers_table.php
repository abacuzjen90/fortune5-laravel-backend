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
        Schema::create('str_customer', function (Blueprint $table) {
            $table->id();
            $table->integer("cust_uniq_id");
            $table->string("registered_name");
            $table->string("charge_to");
            $table->string("tin_number")->nullable();
            $table->string("contact_person")->nullable();
            $table->string("address");
            $table->string("mobile_number");
            $table->string("contact_number")->nullable();
            $table->string("branch_id")->nullable();
            $table->string("destination")->nullable();
            $table->double("value_charge")->nullable();
            $table->string("terms")->nullable();
            $table->string("rate_cbm")->nullable();
            $table->double("rate_kilo")->nullable();
            $table->double("airvalue")->nullable();
            $table->double("minimum")->nullable();
            $table->double("advalorem")->nullable();
            $table->double("discount")->nullable();
            $table->double("small_rate")->nullable();
            $table->double("medium_rate")->nullable();
            $table->double("large_rate")->nullable();
            $table->double("parcel_rate")->nullable();
            $table->string("account_type")->nullable();
            $table->string("agency_type")->nullable();
            $table->string("vat")->nullable();
            $table->double("applicable_tax")->nullable();
            $table->double("fcl_value_charge")->nullable();
            $table->double("ftr10")->nullable();
            $table->double("ftr20")->nullable();
            $table->double("ftr40")->nullable();
            $table->double("ftr20_flat")->nullable();
            $table->double("ftr40_flat")->nullable();
            $table->double("wheeler4")->nullable();
            $table->double("wheeler6")->nullable();
            $table->double("wheeler8")->nullable();
            $table->double("wheeler10")->nullable();
            $table->double("freightliner")->nullable();
            $table->double("rolling_cargo")->nullable();
            $table->double("ftr10_value")->nullable();
            $table->double("ftr20_value")->nullable();
            $table->double("ftr40_value")->nullable();
            $table->double("ftr20_flat_value")->nullable();
            $table->double("ftr40_flat_value")->nullable();
            $table->double("wheeler4_value")->nullable();
            $table->double("wheeler6_value")->nullable();
            $table->double("wheeler8_value")->nullable();
            $table->double("wheeler10_value")->nullable();
            $table->double("freightliner_value")->nullable();
            $table->double("rolling_cargo_value")->nullable();
            $table->text("reason")->nullable();
            $table->string("pickup_charge_remarks")->nullable();
            $table->string("customer_dr_attachment")->nullable();
            $table->string("rates_to_apply")->nullable();
            $table->integer("disabled_encoder")->nullable();
            $table->date("date_disabled")->nullable();
            $table->string("status")->nullable();
            $table->string("blacklist_status")->nullable();
            $table->date("date_blacklisted")->nullable();
            $table->string("old_status")->nullable();
            $table->integer("verify")->nullable();
            $table->string("rate_status")->nullable();
            $table->string("rate_status_time")->nullable();
            $table->string("rate_status_date")->nullable();
            $table->string("rate_status_encoder")->nullable();
            $table->integer("blocklist")->nullable();
            $table->string("encoded")->nullable();
            $table->integer("encoder")->nullable();
            $table->integer("user_updated")->nullable();
            $table->integer("deactive_by")->nullable();
            $table->integer("blacklisted_by")->nullable();
            $table->string("update_rate_user")->nullable();
            $table->string("update_rate_time_date")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('str_customer');
    }
};
