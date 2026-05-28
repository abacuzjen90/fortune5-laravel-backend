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
        Schema::create('payroll_employees', function (Blueprint $table) {
            $table->id();
            $table->integer('masterlist_id'); // Ensure this line is present
            $table->string('emp_account_number');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('branch');
            $table->string('employee_type');
            $table->double('basic_pay');
            $table->double('cola');
            $table->date('date_from');
            $table->date('date_to');
            $table->string('total_working_hrs');
            $table->decimal('late', 10, 2)->nullable();
            $table->decimal('absent', 10, 2)->nullable();
            $table->double('total_no_ot');
            $table->double('night_diff');
            $table->double('leave');
            $table->double('worked_sp_holiday');
            $table->double('sp_holiday_ot');
            $table->double('worked_reg_holiday');
            $table->double('reg_holiday_ot');
            $table->double('worked_restday');
            $table->double('restday_ot');
            $table->decimal('regular_pay', 10, 2)->nullable();
            $table->decimal('cola_pay', 10, 2)->nullable();
            $table->decimal('reg_overtime_pay', 10, 2)->nullable();
            $table->decimal('sp_holiday_pay', 10, 2)->nullable();
            $table->decimal('sp_holiday_ot_pay', 10, 2)->nullable();
            $table->decimal('reg_holiday_pay', 10, 2)->nullable();
            $table->decimal('reg_holiday_ot_pay', 10, 2)->nullable();
            $table->decimal('restday_pay', 10, 2)->nullable();
            $table->decimal('restday_ot_pay', 10, 2)->nullable();
            $table->decimal('night_diff_pay', 10, 2)->nullable();
            $table->decimal('leave_with_pay', 10, 2)->nullable();
            $table->decimal('other_income', 10, 2)->nullable();
            $table->decimal('gross_pay', 10, 2)->nullable();
            $table->decimal('canteen', 10, 2)->nullable();
            $table->string('remittance_branch_code')->nullable();
            // GOVERNMENT SHIT
            $table->decimal('SSS_EE', 10, 2)->nullable();
            $table->decimal('SSS_ER', 10, 2)->nullable();
            $table->decimal('sss_loan', 10, 2)->nullable();
            $table->decimal('sss_loan_amount', 10, 2)->nullable();
            $table->decimal('sss_calamity', 10, 2)->nullable();
            $table->decimal('sss_lrp', 10, 2)->nullable();

            $table->decimal('PHIP_PREMIUM', 10, 2)->nullable();
            $table->decimal('mp2', 10, 2)->nullable();
            $table->decimal('pag_ibig_prem', 10, 2)->nullable();
            $table->decimal('hdmf_loan', 10, 2)->nullable();

            $table->decimal('cash_loan', 10, 2)->nullable();
            $table->decimal('cash_loan_amount', 10, 2)->nullable();
            $table->decimal('cash_bond', 10, 2)->nullable();
            $table->decimal('emp_liab', 10, 2)->nullable();
            $table->decimal('emp_liab_amount', 10, 2)->nullable();
            $table->decimal('health_card', 10, 2)->nullable();
            $table->decimal('calamity', 10, 2)->nullable();

            $table->decimal('tax', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_employees');
    }
};
