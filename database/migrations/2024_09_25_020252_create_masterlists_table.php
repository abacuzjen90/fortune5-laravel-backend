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
        Schema::create('emp_masterlist', function (Blueprint $table) {
            $table->id();
            $table->integer("masterlist_id")->nullable();
            $table->string("first_name");
            $table->string("middle_name")->nullable();
            $table->string("last_name");
            $table->string("employee_type")->nullable();
            $table->string("branch");
            $table->string("department");
            $table->string("section")->nullable();
            $table->string("designation");
            $table->string("employment_status")->nullable();
            $table->text("address")->nullable();
            $table->string("dateofbirth");
            $table->integer("age")->nullable();
            $table->string("gender");
            $table->string("contact_number")->nullable();
            $table->string("pagibig")->nullable();
            $table->string("sss")->nullable();
            $table->string("philhealth")->nullable();
            $table->string("tin")->nullable();
            $table->string("salary_type")->nullable();
            $table->double("salary")->nullable();
            $table->decimal('basic_pay', 10, 2)->nullable();
            $table->decimal("cola", 10, 2)->nullable();
            $table->text("remarks")->nullable();
            $table->string("plate_no")->nullable();
            $table->string("account_locked")->nullable();
            $table->string("datehired");
            $table->string("evaluated")->nullable();
            $table->string("dateregularized");
            $table->string("dateTRS")->nullable();
            $table->integer("status")->nullable();

            //Loan Fields
            $table->double("pag_ibig_prem", 10, 2)->nullable();
            $table->double("cash_loan", 10, 2)->nullable();
            $table->double("cash_bond", 10, 2)->nullable();
            $table->double("sss_loan", 10, 2)->nullable();
            $table->double("mp2", 10, 2)->nullable();
            $table->double("emp_liab", 10, 2)->nullable();
            $table->double("health_card", 10, 2)->nullable();
            $table->double("sss_calamity", 10, 2)->nullable();
            $table->double("sss_lrp", 10, 2)->nullable();
            $table->double("hdmf_loan", 10, 2)->nullable();
            $table->double("calamity", 10, 2)->nullable();
            $table->double("cash_loan_amount", 10, 2)->nullable();
            $table->double("cash_bond_amount", 10, 2)->nullable();
            $table->double("sss_loan_amount", 10, 2)->nullable();
            $table->double("mp2_amount", 10, 2)->nullable();
            $table->double("hdmf_loan_amount", 10, 2)->nullable();
            $table->double("sss_calamity_amount", 10, 2)->nullable();
            $table->double("health_card_amount", 10, 2)->nullable();
            $table->double("sss_lrp_amount", 10, 2)->nullable();
            $table->double("calamity_amount", 10, 2)->nullable();
            $table->double("cash_loan_term", 10, 2)->nullable();
            $table->double("cash_bond_term", 10, 2)->nullable();
            $table->double("sss_loan_term", 10, 2)->nullable();
            $table->double("mp2_term", 10, 2)->nullable();
            $table->double("hdmf_loan_term", 10, 2)->nullable();
            $table->double("sss_calamity_term", 10, 2)->nullable();
            $table->double("health_card_term", 10, 2)->nullable();
            $table->double("sss_lrp_term", 10, 2)->nullable();
            $table->double("calamity_term", 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emp_masterlist');
    }
};
