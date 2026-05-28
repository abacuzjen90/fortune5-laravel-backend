<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'masterlist_id',
        'emp_account_number',
        'first_name',
        'middle_name',
        'last_name',
        'branch',
        'employee_type',
        'basic_pay',
        'cola',
        'date_from',
        'date_to',
        'total_working_hrs',
        'late',
        'absent',
        'total_no_ot',
        'night_diff',
        'leave',
        'worked_sp_holiday',
        'sp_holiday_ot',
        'worked_reg_holiday',
        'reg_holiday_ot',
        'worked_restday',
        'restday_ot',
        'regular_pay',
        'cola_pay',
        'reg_overtime_pay',
        'sp_holiday_pay',
        'sp_holiday_ot_pay',
        'reg_holiday_pay',
        'reg_holiday_ot_pay',
        'restday_pay',
        'restday_ot_pay',
        'night_diff_pay',
        'leave_with_pay',
        'other_income',
        'gross_pay',
        'canteen',
        'remittance_branch_code',
        // ADDED FOR UPDATING LOANS
        'cash_loan_amount',
        'emp_liab_amount',
        'tax',
    ];
}
