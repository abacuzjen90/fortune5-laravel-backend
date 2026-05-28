<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PayrollEmployee;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class PayrollEmployeeController extends Controller
{


    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
    
            //Validate the file type
            $request->validate([
                'file' => 'required|mimes:csv,txt,text/csv,application/vnd.ms-excel|max:2048',
            ]);
            
            // Load the spreadsheet
            $spreadsheet = IOFactory::load($file->getPathname());
    
            // Get the active sheet
            $sheet = $spreadsheet->getActiveSheet();
    
            // Get the highest row number
            $highestRow = $sheet->getHighestRow();
    
            try {
                // Loop through each row, starting from the second row (skip header)
                for ($row = 2; $row <= $highestRow; $row++) {
                    $masterlist_id = $sheet->getCell("A$row")->getValue();
                    $emp_account_number = $sheet->getCell("B$row")->getValue();
                    $first_name = $sheet->getCell("C$row")->getValue();
                    $middle_name = $sheet->getCell("D$row")->getValue();
                    $last_name = $sheet->getCell("E$row")->getValue();
                    $branch = $sheet->getCell("F$row")->getValue();
                    $employee_type = $sheet->getCell("G$row")->getValue();
                    $basic_pay = $sheet->getCell("H$row")->getValue();
                    $cola = $sheet->getCell("I$row")->getValue();
                    $date_from = $sheet->getCell("J$row")->getValue();
                    $date_to = $sheet->getCell("K$row")->getValue();
                    $total_working_hrs = $sheet->getCell("L$row")->getValue();
                    $late = $sheet->getCell("M$row")->getValue();
                    $absent = $sheet->getCell("N$row")->getValue();
                    $total_no_ot = $sheet->getCell("O$row")->getValue();
                    $night_diff = $sheet->getCell("P$row")->getValue();
                    $leave = $sheet->getCell("Q$row")->getValue();
                    $worked_sp_holiday = $sheet->getCell("R$row")->getValue();
                    $sp_holiday_ot = $sheet->getCell("S$row")->getValue();
                    $worked_reg_holiday = $sheet->getCell("T$row")->getValue();
                    $reg_holiday_ot = $sheet->getCell("U$row")->getValue();
                    $worked_restday = $sheet->getCell("V$row")->getValue();
                    $restday_ot = $sheet->getCell("W$row")->getValue();
                    $regular_pay = $sheet->getCell("X$row")->getValue();
                    $cola_pay = $sheet->getCell("Y$row")->getValue();
                    $reg_overtime_pay = $sheet->getCell("Z$row")->getValue();
                    $sp_holiday_pay = $sheet->getCell("AA$row")->getValue();
                    $sp_holiday_ot_pay = $sheet->getCell("AB$row")->getValue();
                    $reg_holiday_pay = $sheet->getCell("AC$row")->getValue();
                    $reg_holiday_ot_pay = $sheet->getCell("AD$row")->getValue();
                    $restday_pay = $sheet->getCell("AE$row")->getValue();
                    $restday_ot_pay = $sheet->getCell("AF$row")->getValue();
                    $night_diff_pay = $sheet->getCell("AG$row")->getValue();
                    $leave_with_pay = $sheet->getCell("AH$row")->getValue();
                    $other_income = $sheet->getCell("AI$row")->getValue();
                    $gross_pay = $sheet->getCell("AJ$row")->getValue();
                    $canteen = $sheet->getCell("AK$row")->getValue();
                    $remittance_branch_code = $sheet->getCell("AL$row")->getValue();
                    //GOVERNMENT SHIT
                    $SSS_EE = $sheet->getCell("AM$row")->getValue();
                    $SSS_ER = $sheet->getCell("AN$row")->getValue();
                    $sss_loan = $sheet->getCell("AO$row")->getValue();
                    $sss_loan_amount = $sheet->getCell("AP$row")->getValue();
                    $sss_calamity = $sheet->getCell("AQ$row")->getValue();
                    $sss_lrp = $sheet->getCell("AR$row")->getValue();

                    $PHIP_PREMIUM = $sheet->getCell("AS$row")->getValue();
                    $mp2 = $sheet->getCell("AT$row")->getValue();
                    $pag_ibig_prem = $sheet->getCell("AU$row")->getValue();
                    $hdmf_loan = $sheet->getCell("AV$row")->getValue();

                    $cash_loan = $sheet->getCell("AW$row")->getValue();
                    $cash_loan_amount = $sheet->getCell("AX$row")->getValue();
                    $cash_bond = $sheet->getCell("AY$row")->getValue();
                    $emp_liab = $sheet->getCell("AZ$row")->getValue();
                    $emp_liab_amount = $sheet->getCell("BA$row")->getValue();
                    $health_card = $sheet->getCell("BB$row")->getValue();
                    $calamity = $sheet->getCell("BC$row")->getValue();
                    $tax = $sheet->getCell("BD$row")->getValue();


                    // Check if it's a numeric value (which would be an Excel serial date)
                    if (is_numeric($date_from)) {
                        // Convert Excel date serial to PHP DateTime and format as Y-m-d
                        $date_from = ExcelDate::excelToDateTimeObject($date_from)->format('Y-m-d');
                    }

                    if (is_numeric($date_to)) {
                        // Convert Excel date serial to PHP DateTime and format as Y-m-d
                        $date_to = ExcelDate::excelToDateTimeObject($date_to)->format('Y-m-d');
                    }

                    // If date is in string format, ensure it's converted to Y-m-d format
                    if (!is_numeric($date_from) && strtotime($date_from)) {
                        $date_from = date('Y-m-d', strtotime($date_from));
                    }

                    if (!is_numeric($date_to) && strtotime($date_to)) {
                        $date_to = date('Y-m-d', strtotime($date_to));
                    }

                    // if (empty($row['master_list_id']) || empty($row['full_name']) || empty($row['branch']) || empty($row['payroll_type']) ||
                    // empty($row['basic_pay']) || empty($row['date_from']) || empty($row['date_to']) || empty($row['total_working_hrs'])  ||
                    // empty($row['cola_pay']) || empty($row['regular_pay'])  ) {
    
                    // return null;  // Skip the row
                    // }

                
                    // Insert the data into the database
                    PayrollEmployee::create([
                        'masterlist_id'          => $masterlist_id,
                        'emp_account_number'     => $emp_account_number,
                        'first_name'             => $first_name,
                        'middle_name'            => $middle_name,
                        'last_name'              => $last_name,
                        'branch'                 => $branch,
                        'employee_type'          => $employee_type,
                        'basic_pay'              => $basic_pay,
                        'cola'                   => $cola,
                        'date_from'              => $date_from,
                        'date_to'                => $date_to,
                        'total_working_hrs'      => $total_working_hrs,
                        'late'                   => $late,
                        'absent'                 => $absent,
                        'total_no_ot'            => $total_no_ot,
                        'night_diff'             => $night_diff,
                        'leave'                  => $leave,
                        'worked_sp_holiday'      => $worked_sp_holiday,
                        'sp_holiday_ot'          => $sp_holiday_ot,
                        'worked_reg_holiday'     => $worked_reg_holiday,
                        'reg_holiday_ot'         => $reg_holiday_ot,
                        'worked_restday'         => $worked_restday,
                        'restday_ot'             => $restday_ot,
                        'regular_pay'            => $regular_pay,
                        'cola_pay'               => $cola_pay,
                        'reg_overtime_pay'       => $reg_overtime_pay,
                        'sp_holiday_pay'         => $sp_holiday_pay,
                        'sp_holiday_ot_pay'      => $sp_holiday_ot_pay,
                        'reg_holiday_pay'        => $reg_holiday_pay,
                        'reg_holiday_ot_pay'     => $reg_holiday_ot_pay,
                        'restday_pay'            => $restday_pay,
                        'restday_ot_pay'         => $restday_ot_pay,
                        'night_diff_pay'         => $night_diff_pay,
                        'leave_with_pay'         => $leave_with_pay,
                        'other_income'           => $other_income,
                        'gross_pay'              => $gross_pay,
                        'canteen'                => $canteen,
                        'remittance_branch_code' => $remittance_branch_code,
                        // GOVERNMENT SHIT
                        'SSS_EE'                 => $SSS_EE,
                        'SSS_ER'                 => $SSS_ER,
                        'sss_loan'               => $sss_loan,
                        'sss_loan_amount'        => $sss_loan_amount,
                        'sss_calamity'           => $sss_calamity,
                        'sss_lrp'                => $sss_lrp,

                        'PHIP_PREMIUM'           => $PHIP_PREMIUM,
                        'mp2'                    => $mp2,
                        'pag_ibig_prem'          => $pag_ibig_prem,
                        'hdmf_loan'              => $hdmf_loan,

                        'cash_loan'              => $cash_loan,
                        'cash_loan_amount'       => $cash_loan_amount,
                        'cash_bond'              => $cash_bond,
                        'emp_liab'               => $emp_liab,
                        'emp_liab_amount'        => $emp_liab_amount,
                        'health_card'            => $health_card,
                        'calamity'               => $calamity,
                        'tax'                    => $tax,
                    ]);
                }
    
                return response()->json([
                    'message' => 'Employee details imported successfully from file',
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Failed to import employee details from file',
                    'error' => $e->getMessage(),
                ], 500);
            }
        }
    
        return response()->json([
            'message' => 'No file uploaded',
        ], 400);
    }
}