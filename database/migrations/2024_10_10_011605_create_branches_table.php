<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('str_list', function (Blueprint $table) {
            $table->id();
            $table->string("str_list_id");
            $table->string("head_person");
            $table->integer("branchtype_id");
            $table->string("branchtype");
            $table->string("acronym");
            $table->text("description")->nullable();
            $table->string("str_list_address");
            $table->string("contact_number");
            $table->double("per_cbm")->nullable();
            $table->double("per_kilo")->nullable();
            $table->double("val_charge")->nullable();
            $table->double("fcl_value_charge")->nullable();
            $table->double("min_charge")->nullable();
            $table->double("advalorem")->nullable();
            $table->double("ftr10")->nullable();
            $table->double("ftr20")->nullable();
            $table->double("ftr40")->nullable();
            $table->double("wheeler4")->nullable();
            $table->double("wheeler6")->nullable();
            $table->double("wheeler8")->nullable();
            $table->double("wheeler10")->nullable();
            $table->double("freightliner")->nullable();
            $table->double("rolling_cargo")->nullable();
            $table->double("ftr10_value")->nullable();
            $table->double("ftr20_value")->nullable();
            $table->double("ftr40_value")->nullable();
            $table->double("wheeler4_value")->nullable();
            $table->double("wheeler6_value")->nullable();
            $table->double("wheeler8_value")->nullable();
            $table->double("wheeler10_value")->nullable();
            $table->double("freightliner_value")->nullable();
            $table->double("rolling_cargo_value")->nullable();
            $table->double("airvalue")->nullable();
            $table->integer("management_fee")->nullable();
            $table->integer("agency_10ftr")->nullable();
            $table->integer("agency_20ftr")->nullable();
            $table->integer("agency_40ftr")->nullable();
            $table->double("small_rate")->nullable();
            $table->double("medium_rate")->nullable();
            $table->double("large_rate")->nullable();
            $table->double("parcel_rate")->nullable();
            $table->string("status")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('str_list');
    }
};
