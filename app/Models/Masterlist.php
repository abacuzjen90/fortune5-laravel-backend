<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masterlist extends Model
{
    use HasFactory;

    protected $table = 'emp_masterlist';

    protected $fillable = [
        'masterlist_id',
        'first_name',
        'middle_name',
        'last_name',
        'employee_type',
        'branch',
        'department',
        'section',
        'designation',
        'employment_status',
        'address',
        'dateofbirth',
        'age',
        'gender',
        'contact_number',
        'pagibig',
        'sss',
        'philhealth',
        'tin',
        'salary_type',
        'salary',
        'basic_pay',
        'cola',
        'remarks',
        'plate_no',
        'account_locked',
        'datehired',
        'evaluated',
        'dateregularized',
        'dateTRS',
        'status',
        'pag_ibig_prem',
        'cash_loan',
        'cash_bond',
        'sss_loan',
        'mp2',
        'emp_liab',
        'health_card',
        'sss_calamity',
        'sss_lrp',
        'hdmf_loan',
        'calamity',

        'cash_loan_amount',
        'cash_bond_amount',
        'sss_loan_amount',
        'mp2_amount',
        'hdmf_loan_amount',
        'sss_calamity_amount',
        'health_card_amount',
        'sss_lrp_amount',
        'calamity_amount',
        'cash_loan_term',
        'cash_bond_term',
        'sss_loan_term',
        'mp2_term',
        'hdmf_loan_term',
        'sss_calamity_term',
        'health_card_term',
        'sss_lrp_term',
        'calamity_term',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
