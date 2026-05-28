<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayrollEmployee;
use App\Models\Masterlist;

class PayrollController extends Controller
{
    
    public function getEmployees(Request $request)
    {
        $employees = Masterlist::all()->map(function ($employee) {
            return [
                'id' => $employee->id,
                'name' => $employee->first_name . ' ' . $employee->last_name,
            ];
        });

        return response()->json($employees);
    }

    

    public function filterPayroll(Request $request)
    {
        $query = PayrollEmployee::query();

        // Apply filters
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->where('date_from', $request->date_from)
                  ->where('date_to', $request->date_to);
        }
        if ($request->branch !== 'all') {
            $query->where('branch', $request->branch);
        }

        if ($request->employee_type !== 'all') {
            $query->where('employee_type', $request->employee_type);
        }

        $payrolls = $query->get()->map(function ($employee) {
            $employee->employee_name = $employee->first_name . ' ' . $employee->last_name;

            $employee->gross_pay = $employee->regular_pay + $employee->cola_pay + $employee->reg_overtime_pay
                + $employee->sp_holiday_pay + $employee->sp_holiday_ot_pay
                + $employee->reg_holiday_pay + $employee->reg_holiday_ot_pay
                + $employee->restday_pay + $employee->restday_ot_pay
                + $employee->night_diff_pay + $employee->leave_with_pay
                + $employee->other_income;

            $employee->net_pay = $employee->gross_pay - $employee->canteen;

            return $employee;
        });

        return response()->json($payrolls);
    }

    public function saveAllSSSEE(Request $request)
    {
        $SSSEEData = $request->input('SSSEEData', []); // SSS_EE data
        $SSSERData = $request->input('SSSERData', []); // SSS_ER data
        $PHIPData = $request->input('PHIPData', []);   // PHIP data
    
        foreach ($SSSEEData as $id => $SSS_EE) {
            $payrollEmployee = PayrollEmployee::findOrFail($id);
            $payrollEmployee->SSS_EE = $SSS_EE;
    
            if (isset($SSSERData[$id])) {
                $payrollEmployee->SSS_ER = $SSSERData[$id];
            }
    
            if (isset($PHIPData[$id])) {
                $payrollEmployee->PHIP_PREMIUM = $PHIPData[$id];
            }
    
            $payrollEmployee->save();
        }
    
        return response()->json(['message' => 'All SSS_EE, SSS_ER, and PHIP_PREMIUM values saved successfully']);
    }




    public function calculateSSSEE(Request $request)
    {
        $id = $request->input('id');
        $payrollEmployee = PayrollEmployee::find($id);
    
        if (!$payrollEmployee) {
            return response()->json(['error' => 'Employee not found'], 404);
        }
    
        $basicPay = $payrollEmployee->basic_pay;
        $cola = $payrollEmployee->cola;
    
        $totalPay = $basicPay + $cola;
    
        $ranges = [
            ['min' => 1.00, 'max' => 5249.99, 'SSS_EE' => 250.00, 'SSS_ER' => 510.00],
            ['min' => 5250.00, 'max' => 5749.99, 'SSS_EE' => 275.00, 'SSS_ER' => 560.00],
            ['min' => 5750.00, 'max' => 6249.99, 'SSS_EE' => 300.00, 'SSS_ER' => 610.00],
            ['min' => 6250.00, 'max' => 6749.99, 'SSS_EE' => 325.00, 'SSS_ER' => 660.00],
            ['min' => 6750.00, 'max' => 7249.99, 'SSS_EE' => 350.00, 'SSS_ER' => 710.00],
            ['min' => 7250.00, 'max' => 7749.99, 'SSS_EE' => 375.00, 'SSS_ER' => 760.00],
            ['min' => 7750.00, 'max' => 8249.99, 'SSS_EE' => 400.00, 'SSS_ER' => 810.00],
            ['min' => 8250.00, 'max' => 8749.99, 'SSS_EE' => 425.00, 'SSS_ER' => 860.00],
            ['min' => 8750.00, 'max' => 9249.99, 'SSS_EE' => 450.00, 'SSS_ER' => 910.00],
            ['min' => 9250.00, 'max' => 9749.99, 'SSS_EE' => 475.00, 'SSS_ER' => 960.00],
            ['min' => 9750.00, 'max' => 10249.99, 'SSS_EE' => 500.00, 'SSS_ER' => 1010.00],
            ['min' => 10250.00, 'max' => 10749.99, 'SSS_EE' => 525.00, 'SSS_ER' => 1060.00],
            ['min' => 10750.00, 'max' => 11249.99, 'SSS_EE' => 550.00, 'SSS_ER' => 1110.00],
            ['min' => 11250.00, 'max' => 11749.99, 'SSS_EE' => 575.00, 'SSS_ER' => 1160.00],
            ['min' => 11750.00, 'max' => 12249.99, 'SSS_EE' => 600.00, 'SSS_ER' => 1210.00],
            ['min' => 12250.00, 'max' => 12749.99, 'SSS_EE' => 625.00, 'SSS_ER' => 1260.00],
            ['min' => 12750.00, 'max' => 13249.99, 'SSS_EE' => 650.00, 'SSS_ER' => 1310.00],
            ['min' => 13250.00, 'max' => 13749.99, 'SSS_EE' => 675.00, 'SSS_ER' => 1360.00],
            ['min' => 13750.00, 'max' => 14249.99, 'SSS_EE' => 700.00, 'SSS_ER' => 1410.00],
            ['min' => 14250.00, 'max' => 14749.99, 'SSS_EE' => 725.00, 'SSS_ER' => 1460.00],
            ['min' => 14750.00, 'max' => 15249.99, 'SSS_EE' => 750.00, 'SSS_ER' => 1530.00],
            ['min' => 15250.00, 'max' => 15749.99, 'SSS_EE' => 775.00, 'SSS_ER' => 1580.00],
            ['min' => 15750.00, 'max' => 16249.99, 'SSS_EE' => 800.00, 'SSS_ER' => 1630.00],
            ['min' => 16250.00, 'max' => 16749.99, 'SSS_EE' => 825.00, 'SSS_ER' => 1680.00],
            ['min' => 16750.00, 'max' => 17249.99, 'SSS_EE' => 850.00, 'SSS_ER' => 1730.00],
            ['min' => 17250.00, 'max' => 17749.99, 'SSS_EE' => 875.00, 'SSS_ER' => 1780.00],
            ['min' => 17750.00, 'max' => 18249.99, 'SSS_EE' => 900.00, 'SSS_ER' => 1830.00],
            ['min' => 18250.00, 'max' => 18749.99, 'SSS_EE' => 925.00, 'SSS_ER' => 1880.00],
            ['min' => 18750.00, 'max' => 19249.99, 'SSS_EE' => 950.00, 'SSS_ER' => 1930.00],
            ['min' => 19250.00, 'max' => 19749.99, 'SSS_EE' => 975.00, 'SSS_ER' => 1980.00],
            ['min' => 19750.00, 'max' => 20249.99, 'SSS_EE' => 1000.00, 'SSS_ER' => 2030.00],
            ['min' => 20250.00, 'max' => 20749.99, 'SSS_EE' => 1025.00, 'SSS_ER' => 2080.00],
            ['min' => 20750.00, 'max' => 21249.99, 'SSS_EE' => 1050.00, 'SSS_ER' => 2130.00],
            ['min' => 21250.00, 'max' => 21749.99, 'SSS_EE' => 1075.00, 'SSS_ER' => 2180.00],
            ['min' => 21750.00, 'max' => 22249.99, 'SSS_EE' => 1100.00, 'SSS_ER' => 2230.00],
            ['min' => 22250.00, 'max' => 22749.99, 'SSS_EE' => 1125.00, 'SSS_ER' => 2280.00],
            ['min' => 22750.00, 'max' => 23249.99, 'SSS_EE' => 1150.00, 'SSS_ER' => 2330.00],
            ['min' => 23250.00, 'max' => 23749.99, 'SSS_EE' => 1175.00, 'SSS_ER' => 2380.00],
            ['min' => 23750.00, 'max' => 24249.99, 'SSS_EE' => 1200.00, 'SSS_ER' => 2430.00],
            ['min' => 24250.00, 'max' => 24749.99, 'SSS_EE' => 1225.00, 'SSS_ER' => 2480.00],
            ['min' => 24750.00, 'max' => 25249.99, 'SSS_EE' => 1250.00, 'SSS_ER' => 2530.00],
            ['min' => 25250.00, 'max' => 25749.99, 'SSS_EE' => 1275.00, 'SSS_ER' => 2580.00],
            ['min' => 25750.00, 'max' => 26249.99, 'SSS_EE' => 1300.00, 'SSS_ER' => 2630.00],
            ['min' => 26250.00, 'max' => 26749.99, 'SSS_EE' => 1325.00, 'SSS_ER' => 2680.00],
            ['min' => 26750.00, 'max' => 27249.99, 'SSS_EE' => 1350.00, 'SSS_ER' => 2730.00],
            ['min' => 27250.00, 'max' => 27749.99, 'SSS_EE' => 1375.00, 'SSS_ER' => 2780.00],
            ['min' => 27750.00, 'max' => 28249.99, 'SSS_EE' => 1400.00, 'SSS_ER' => 2830.00],
            ['min' => 28250.00, 'max' => 28749.99, 'SSS_EE' => 1425.00, 'SSS_ER' => 2880.00],
            ['min' => 28750.00, 'max' => 29249.99, 'SSS_EE' => 1450.00, 'SSS_ER' => 2930.00],
            ['min' => 29250.00, 'max' => 29749.99, 'SSS_EE' => 1475.00, 'SSS_ER' => 2980.00],
            ['min' => 29750.00, 'max' => 30249.99, 'SSS_EE' => 1500.00, 'SSS_ER' => 3030.00],
            ['min' => 30250.00, 'max' => 30749.99, 'SSS_EE' => 1525.00, 'SSS_ER' => 3080.00],
            ['min' => 30750.00, 'max' => 31249.99, 'SSS_EE' => 1550.00, 'SSS_ER' => 3130.00],
            ['min' => 31250.00, 'max' => 31749.99, 'SSS_EE' => 1575.00, 'SSS_ER' => 3180.00],
            ['min' => 31750.00, 'max' => 32249.99, 'SSS_EE' => 1600.00, 'SSS_ER' => 3230.00],
            ['min' => 32250.00, 'max' => 32749.99, 'SSS_EE' => 1625.00, 'SSS_ER' => 3280.00],
            ['min' => 32750.00, 'max' => 33249.99, 'SSS_EE' => 1650.00, 'SSS_ER' => 3330.00],
            ['min' => 33250.00, 'max' => 33749.99, 'SSS_EE' => 1675.00, 'SSS_ER' => 3380.00],
            ['min' => 33750.00, 'max' => 34249.99, 'SSS_EE' => 1700.00, 'SSS_ER' => 3430.00],
            ['min' => 34250.00, 'max' => 34749.99, 'SSS_EE' => 1725.00, 'SSS_ER' => 3480.00],
            ['min' => 34750.00, 'max' => 100000000.00, 'SSS_EE' => 1750.00, 'SSS_ER' => 3530.00],
        ];
    
        $SSS_EE = 0;
        $SSS_ER = 0;

        $phip_premium = round(($basicPay * 0.05) / 2, 2);
    
        foreach ($ranges as $range) {
            if ($totalPay >= $range['min'] && $totalPay <= $range['max']) {
                $SSS_EE = $range['SSS_EE'];
                $SSS_ER = $range['SSS_ER'];
                break;
            }
        }
    
        return response()->json([
            'SSS_EE' => $SSS_EE,
            'SSS_ER' => $SSS_ER,
            'PHIP_PREMIUM' => $phip_premium
        ]);
    }
    

    public function calculatePagibig(Request $request)
    {
        $id = $request->input('id');
        $payrollEmployee = Masterlist::find($id);
    
        if (!$payrollEmployee) {
            return response()->json(['PagIBIG' => 'N/A']);
        }
    
        $pagibig = $payrollEmployee->pag_ibig_prem ?? 'N/A';
    
        return response()->json([
            'PagIBIG' => $pagibig
        ]);
    }
    

    public function savePagibig(Request $request)
    {
        try {
            $PagIBIGData = $request->input('PagIBIGData', []);
    
            foreach ($PagIBIGData as $id => $pagibig) {
                $employee = Masterlist::find($id); 
    
                if ($employee) {
                    $employee->pag_ibig_prem = $pagibig; 
                    $employee->save();
                }
            }
    
            return response()->json(['message' => 'Pag-IBIG values saved successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while saving Pag-IBIG values',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    

    public function calculateLoans(Request $request)
    {
        $payrollEmployee = PayrollEmployee::find($request->input('id'));

        if (!$payrollEmployee) {
            return response()->json(['loans' => []]);
        }

        // Fetch the employee's masterlist record
        $masterlist = Masterlist::where('masterlist_id', $payrollEmployee->masterlist_id)->first();

        if (!$masterlist) {
            return response()->json(['loans' => []]);
        }

        $loans = [
            // 'cash_loan' => $masterlist->cash_loan ?? 'N/A',
            'cash_bond_amount' => $masterlist->cash_bond_amount ?? 'N/A',
            'sss_loan' => $masterlist->sss_loan ?? 'N/A',
            'sss_loan_amount' => $masterlist->sss_loan_amount ?? 'N/A',
            'mp2_amount' => $masterlist->mp2_amount ?? 'N/A',
            // 'emp_liab' => $masterlist->emp_liab ?? 'N/A',
            'hdmf_loan_amount' => $masterlist->hdmf_loan_amount ?? 'N/A',
            'sss_calamity_amount' => $masterlist->sss_calamity_amount ?? 'N/A',
            'health_card' => $masterlist->health_card ?? 'N/A',
            'sss_lrp_amount' => $masterlist->sss_lrp_amount ?? 'N/A',
            'calamity_amount' => $masterlist->calamity_amount ?? 'N/A',
        ];

        return response()->json(['loans' => $loans]);
    }


    public function saveLoans(Request $request)
    {
        $request->validate([
            'LoanData' => 'required|array',
        ]);
    
        $LoanData = $request->input('LoanData', []);
        $errors = [];
    
        foreach ($LoanData as $id => $loans) {
            $payrollEmployee = PayrollEmployee::find($id);
    
            if (!$payrollEmployee) {
                $errors[] = "Employee with ID $id not found";
                continue;
            }
    
            // Ensure numeric fields are stored correctly
            // $payrollEmployee->cash_loan = is_numeric($loans['cash_loan'] ?? null) ? (float) $loans['cash_loan'] : null;
            $payrollEmployee->cash_bond_amount = is_numeric($loans['cash_bond_amount'] ?? null) ? (float) $loans['cash_bond_amount'] : null; //computation

            $payrollEmployee->sss_loan = is_numeric($loans['sss_loan'] ?? null) ? (float) $loans['sss_loan'] : null; //balance
            $payrollEmployee->sss_loan_amount = is_numeric($loans['sss_loan_amount'] ?? null) ? (float) $loans['sss_loan_amount'] : null; //with computation
            
            // $payrollEmployee->mp2 = is_numeric($loans['mp2'] ?? null) ? (float) $loans['mp2'] : null;
            $payrollEmployee->mp2_amount = is_numeric($loans['mp2_amount'] ?? null) ? (float) $loans['mp2_amount'] : null; //computation

            // $payrollEmployee->emp_liab = is_numeric($loans['emp_liab'] ?? null) ? (float) $loans['emp_liab'] : null;

            $payrollEmployee->hdmf_loan_amount = is_numeric($loans['hdmf_loan_amount'] ?? null) ? (float) $loans['hdmf_loan_amount'] : null;//computation

            $payrollEmployee->sss_calamity_amount = is_numeric($loans['sss_calamity_amount'] ?? null) ? (float) $loans['sss_calamity_amount'] : null;//computation

            $payrollEmployee->health_card = is_numeric($loans['health_card'] ?? null) ? (float) $loans['health_card'] : null;//computation
            
            $payrollEmployee->sss_lrp_amount = is_numeric($loans['sss_lrp_amount'] ?? null) ? (float) $loans['sss_lrp_amount'] : null;//computation
            
            $payrollEmployee->calamity_amount = is_numeric($loans['calamity_amount'] ?? null) ? (float) $loans['calamity_amount'] : null;//computation
    
            // Disable automatic timestamps if needed
            $payrollEmployee->timestamps = false;
    
            $payrollEmployee->save();
        }
    
        return response()->json(['message' => 'Loan values saved successfully']);
    }
    

    public function calculateCashLoanAndEmpLiab(Request $request)
    {
        $payrollEmployee = PayrollEmployee::find($request->id);
    
        if (!$payrollEmployee) {
            return response()->json([
                'cash_loan' => 0.00,
                'emp_liab' => 0.00,
            ]);
        }
    
        $employee = Masterlist::where('id', $payrollEmployee->masterlist_id)->first();
    
        if (!$employee) {
            return response()->json([
                'cash_loan' => 0.00,
                'emp_liab' => 0.00,
            ]);
        }

        if ($employee->cash_loan == 0.00 && $employee->emp_liab == 0.00) {
            return response()->json([
                'cash_loan' => 0.00,
                'emp_liab' => 0.00,
            ]);
        }
    
        $grossPay = $payrollEmployee->gross_pay;
        $canteen = $payrollEmployee->canteen ?? 0;
        $calamity = $payrollEmployee->calamity_amount ?? 0;
        $hdmfLoan = $payrollEmployee->hdmf_loan ?? 0;
        $healthCard = $payrollEmployee->health_card ?? 0;
        $empLiab = $payrollEmployee->emp_liab ?? 0;
        $cashBond = $payrollEmployee->cash_bond_amount ?? 0;
        $pagIbigPrem = $payrollEmployee->pag_ibig_prem ?? 0;
        $mp2 = $payrollEmployee->mp2_amount ?? 0;
        $sssLrp = $payrollEmployee->sss_lrp_amount ?? 0;
        $sssCal = $payrollEmployee->sss_calamity_amount ?? 0;
        $sssLoanAmount = $payrollEmployee->sss_loan_amount ?? 0;
        $sssEe = $payrollEmployee->SSS_EE ?? 0;
    
        $totalDeductions = $canteen + $calamity + $hdmfLoan + $healthCard + $empLiab + $cashBond + $pagIbigPrem + $mp2 + $sssLrp + $sssCal + $sssLoanAmount + $sssEe;
    
        $totalDeductionGrossPay = $grossPay - $totalDeductions;

        if ($employee->cash_loan > 0 && $employee->emp_liab == 0.00) {
            $totalCashLoan = round($totalDeductionGrossPay * 0.30, 2);
            $empLiab = 0.00;
        } elseif ($employee->cash_loan == 0.00 && $employee->emp_liab > 0) {
            $totalCashLoan = 0.00;
            $empLiab = round($totalDeductionGrossPay * 0.20, 2);
        } elseif ($employee->cash_loan > 0 && $employee->emp_liab > 0) {
            $totalCashLoan = round($totalDeductionGrossPay * 0.15, 2);
            $empLiab = round($totalDeductionGrossPay * 0.15, 2);
        } else {
            $totalCashLoan = 0.00;
            $empLiab = 0.00;
        }
    
        return response()->json([
            'cash_loan' => $totalCashLoan,
            'emp_liab' => $empLiab,
        ]);
    }
    

public function saveAllCashLoanAndEmpLiab(Request $request)
{
    $cashLoanData = $request->input('cashLoanData', []);
    $empLiabData = $request->input('empLiabData', []);

    foreach ($cashLoanData as $id => $cashLoan) {
        $payrollEmployee = PayrollEmployee::findOrFail($id);

        $payrollEmployee->cash_loan_amount = $cashLoan;

        // ✅ Add values from emp_masterlist (Masterlist)
        $employee = Masterlist::where('id', $payrollEmployee->masterlist_id)->first();
        if ($employee) {
            $payrollEmployee->cash_loan = $employee->cash_loan;
            $payrollEmployee->emp_liab = $employee->emp_liab;
        }

        if (isset($empLiabData[$id])) {
            $payrollEmployee->emp_liab_amount = $empLiabData[$id];
        }

        $payrollEmployee->save();
    }

    return response()->json(['message' => 'All Cash Loan and Emp Liab values saved successfully']);
}


 public function update(Request $request, $id)
 {
     try {

         \Log::info('Update Request:', $request->all());
 
         $payroll = PayrollEmployee::findOrFail($id);
 
         $validatedData = $request->validate([
             'cash_loan_amount' => 'nullable|numeric|min:0',
             'emp_liab_amount' => 'nullable|numeric|min:0',
         ]);
 
         \Log::info('Validated Data:', $validatedData);
 
         $payroll->update($validatedData);
 
         return response()->json(['message' => 'Payroll updated successfully'], 200);
     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
         \Log::error('Payroll record not found:', ['id' => $id]);
         return response()->json(['error' => 'Payroll record not found'], 404);
     } catch (\Exception $e) {
         \Log::error('Error updating payroll:', ['message' => $e->getMessage()]);
         return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
     }
 }

 public function saveTax(Request $request)
 {
     $taxData = $request->input('taxData', []); 
 
     foreach ($taxData as $id => $tax) {
         $payrollEmployee = PayrollEmployee::find($id); 
 
         if ($payrollEmployee) {
             $payrollEmployee->update(['tax' => $tax]); 
         }
     }
 
     return response()->json(['message' => 'Tax values saved successfully']);
 }


public function calculateTax(Request $request)
{
    $id = $request->input('id');
    // Retrieve all payroll entries for the employee
    $payrollEntries = PayrollEmployee::where('masterlist_id', $id)->get();

    if ($payrollEntries->isEmpty()) {
        \Log::error('Payroll entries not found for ID:', ['id' => $id]);
        return response()->json(['error' => 'Payroll entries not found for the employee'], 404);
    }

    $totalGrossPay = $payrollEntries->sum('gross_pay'); // Monthly gross pay sum

    // Retrieve deductions (SSS, PHIC, HDMF) from the first entry
    $sssEE = $payrollEntries->first()->SSS_EE ?? 0;
    $phic = $payrollEntries->first()->PHIP_PREMIUM ?? 0;
    $pagIbigPrem = $payrollEntries->first()->pag_ibig_prem ?? 0;

    $annualGross = $totalGrossPay * 12; // Annual gross pay
    $annualSSS = $sssEE * 12; // Annual SSS deduction (if monthly)
    $annualPHIC = $phic * 12; // Annual PHIC deduction (if monthly)
    $annualHDMF = $pagIbigPrem * 12; // Annual Pag-Ibig deduction (if monthly)

    $annualTotalDeduction = $annualSSS + $annualPHIC + $annualHDMF;
    $annualTaxableIncome = $annualGross - $annualTotalDeduction;

    $tax = 0;
    $prescribedPercent = 0;
    $overCompensationLevel = 0;

    // BIR Tax Bracket Logic
    if ($annualTaxableIncome <= 250000) {
        $prescribedWht = 0;
        $prescribedPercent = 0.00;
        $overCompensationLevel = 0;
    } elseif ($annualTaxableIncome <= 400000) {
        $prescribedWht = 0;
        $prescribedPercent = 0.15;
        $overCompensationLevel = $annualTaxableIncome - 250000;
    } elseif ($annualTaxableIncome <= 800000) {
        $prescribedWht = 22500;
        $prescribedPercent = 0.20;
        $overCompensationLevel = $annualTaxableIncome - 400000;
    } elseif ($annualTaxableIncome <= 2000000) {
        $prescribedWht = 102500;
        $prescribedPercent = 0.25;
        $overCompensationLevel = $annualTaxableIncome - 800000;
    } elseif ($annualTaxableIncome <= 8000000) {
        $prescribedWht = 402500;
        $prescribedPercent = 0.30;
        $overCompensationLevel = $annualTaxableIncome - 2000000;
    } else { // above 8,000,000
        $prescribedWht = 2202500;
        $prescribedPercent = 0.35;
        $overCompensationLevel = $annualTaxableIncome - 8000000;
    }

    $annualIncomeTax = $prescribedWht + ($overCompensationLevel * $prescribedPercent);
    $monthlyTax = round($annualIncomeTax / 12, 2);

    return response()->json([
        'total_gross_pay' => $totalGrossPay,
        'tax' => round($monthlyTax, 2)
    ]);
}


}