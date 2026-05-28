<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class LeaveController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['employee_index', 'leave_record', 'deleted_leave_record', 'leave_index', 'leave', 'destroy', 'restore', 'approve', 'disapprove'])
        ];

    }

    // GET MASTELIST EMPLOYEE RECORD
    public function employee_index(){

        return DB::table('emp_masterlist')
                // ->where([['account_locked', '=', 'n']])
                ->orderBy('last_name')
                ->get();

    }

    // GET LEAVE REQUEST RECORD
    public function leave_record(){

        return DB::table('leave_details')
                ->where([['year_of_leave', '>=', date('Y')]])
                ->orderBy('date', 'DESC')
                ->get();
    
    }

    // GET DELETED LEAVE REQUEST RECORD
    public function deleted_leave_record(){

        $date_range = date('Y-m-d', strtotime(now().' - 7 days'));

        return DB::table('deleted_leave_records')
                ->where([['created_at', '>=', $date_range]])
                ->orderBy('date')
                ->get();
    
    }

    // GET AVAILABLE LEAVE TOTAL
    public function leave_index(Request $request){

        $employee_name = DB::table('emp_masterlist')->where([['id', '=', $request->emp_no]])->first();

        $no_of_sick_leave = DB::table('leave_details')->where([
            ['emp_no', '=', $request->emp_no],
            ['year_of_leave', '=', date('Y')],
            ['leave_type', '=', 'sick'],
            ['status', '=', 'a']
        ])->sum('no_of_day');

        $no_of_vacation_leave = DB::table('leave_details')->where([
            ['emp_no', '=', $request->emp_no],
            ['year_of_leave', '=', date('Y')],
            ['leave_type', '=', 'vacation'],
            ['status', '=', 'a']
        ])->sum('no_of_day');

        $no_of_emergency_leave = DB::table('leave_details')->where([
            ['emp_no', '=', $request->emp_no],
            ['year_of_leave', '=', date('Y')],
            ['leave_type', '=', 'emergency'],
            ['status', '=', 'a']
        ])->sum('no_of_day');

        $no_of_paternity_leave = DB::table('leave_details')->where([
            ['emp_no', '=', $request->emp_no],
            ['year_of_leave', '=', date('Y')],
            ['leave_type', '=', 'paternity'],
            ['status', '=', 'a']
        ])->sum('no_of_day');

        $no_of_half_day_leave = DB::table('leave_details')->where([
            ['emp_no', '=', $request->emp_no],
            ['date', '>=', date('Y-m-01')],
            ['date_to', '<=', date('Y-m-31')],
            ['leave_type', '=', 'half-day'],
            ['status', '=', 'a']
        ])->sum('no_of_day');

        return [
            'emp_no'                => $request->emp_no,
            'emp_name'              => $employee_name->last_name.', '.$employee_name->first_name.' '. $employee_name->middle_name,
            'sick_leave_total'      => 10 - $no_of_sick_leave,
            'vacation_leave_total'  => 15 - $no_of_vacation_leave,
            'emergency_leave_total' =>  5 - $no_of_emergency_leave,
            'paternity_leave_total' =>  5 - $no_of_paternity_leave,
            'half_day_leave_total'  =>  2 - ($no_of_half_day_leave * 2)
        ];
    
    }

    // FILE LEAVE REQUEST
    public function leave(Request $request){

        $request->validate([
            'emp_no'           => 'required',
            'emp_name'         => 'required',
            'date'             => 'required',
            'date_to'          => 'required',
            'year_of_leave'    => 'nullable',
            'no_of_day'        => 'required',
            'reason'           => 'required',
            'leave_type'       => 'required',
            'encoder_id'       => 'nullable',
            'encoded_by'       => 'nullable'
        ]);


        if ($request->leave_type != 'half-day'){
            $no_of_filed_leave = DB::table('leave_details')->where([
                ['emp_no', '=', $request->emp_no],
                ['year_of_leave', '=', date('Y')],
                ['leave_type', '=', $request->leave_type],
                ['status', '=', 'a']
            ])->sum('no_of_day');
        }else{
            $no_of_filed_leave = DB::table('leave_details')->where([
                ['emp_no', '=', $request->emp_no],
                ['date', '>=', date('Y-m-01')],
                ['date_to', '<=', date('Y-m-31')],
                ['leave_type', '=', 'half-day'],
                ['status', '=', 'a']
            ])->sum('no_of_day');

            $last_month_cutoff = date('Y-m-26', strtotime(date('Y-m-d').' - 1 months'));
            $next_month_cutoff = date('Y-m-10', strtotime(date('Y-m-d').' + 1 months'));

            $check_last_month_cutoff = DB::table('leave_details')->where([
                ['emp_no', '=', $request->emp_no],
                ['date', '>=', $last_month_cutoff],
                ['date_to', '<=', date('Y-m-10')],
                ['leave_type', '=', 'half-day'],
                ['status', '=', 'a']
            ])->sum('no_of_day');

            $check_current_month_cutoff = DB::table('leave_details')->where([
                ['emp_no', '=', $request->emp_no],
                ['date', '>=', date('Y-m-11')],
                ['date_to', '<=', date('Y-m-25')],
                ['leave_type', '=', 'half-day'],
                ['status', '=', 'a']
            ])->sum('no_of_day');

            $check_next_month_cutoff = DB::table('leave_details')->where([
                ['emp_no', '=', $request->emp_no],
                ['date', '>=', date('Y-m-26')],
                ['date_to', '<=', date($next_month_cutoff)],
                ['leave_type', '=', 'half-day'],
                ['status', '=', 'a']
            ])->sum('no_of_day');

        }
        

        if( $request->leave_type == 'sick' ){
            $max_leave = '10';
        }else if( $request->leave_type == 'vacation' ){
            $max_leave = '15';
        }else if( $request->leave_type == 'half-day' ){
            $max_leave = '2';
        }else{
            $max_leave = '5';
        }

        $year_of_leave    = date_format(date_create($request->date),'Y');
        $year_of_leave_to = date_format(date_create($request->date_to),'Y');

        if( $request->date < $request->date_to ){
                
            $no_of_day = '0';
            
            $diff_day = ((strtotime($request->date_to) - strtotime($request->date)) / (86400));

            for($i = 0; $i <= $diff_day; $i++){
                $get_day = date('D', strtotime($request->date.' + '.$i.' days'));

                if($get_day != 'Sun'){
                    $no_of_day += '1';
                }else{
                    $no_of_day += '0';
                }
            }

        }else{
            $no_of_day = '1';
        }

        
        if( $request->date > $request->date_to ){

            $status  = 'error';
            $message = 'You cannot pick a date on "Date To" that is before the date of "Date From".';

        }else if( $request->leave_type != 'half-day' && $year_of_leave != $year_of_leave_to ){
            
            $status  = 'error';
            $message = 'Please pick the same year for "Date From" and "Date To".';
        
        }else if( date('D', strtotime($request->date)) == 'Sun' && date('D', strtotime($request->date_to)) == 'Sun' ){
            
            $status  = 'error';
            $message = 'You cannot pick the date because that day is Sunday.';

        }else if( $request->leave_type != 'half-day' && $request->no_of_day > $no_of_day ){
            
            $status  = 'error';
            $message = 'The number of days you inputed went over the number of days available for "Date From" and "Date To".';

        }else if( $request->leave_type != 'half-day' && ($no_of_filed_leave == $max_leave || $no_of_filed_leave + $request->no_of_day > $max_leave ) && $year_of_leave == date('Y') ){

            $status  = 'error';
            $message = 'Employee had reached the limit for "'. ucfirst($request->leave_type) .' Leave" that they can file for this year.';
        
        }else if( $request->leave_type == 'half-day' && $request->date != $request->date_to ){
            
            $status  = 'error';
            $message = 'The date for "Date From" and "Date To" must be the same day for "Half-day Leave". Please change then try again.';

        }else if( $request->leave_type == 'half-day' && ($request->date < $last_month_cutoff || $request->date_to > $next_month_cutoff) ){

            $status  = 'error';
            $message = 'You cannot file a "'. ucfirst($request->leave_type) .' Leave" for the selected date.';
        
        }else if( $request->leave_type == 'half-day' && ($no_of_filed_leave * 2) == $max_leave && date('m', strtotime($request->date)) == date('m') ){

            $status  = 'error';
            $message = 'Employee had reached the limit for "'. ucfirst($request->leave_type) .' Leave" that they can file for this month.';
        
        }else if( $request->leave_type == 'half-day' && ($request->date >= $last_month_cutoff && $request->date_to <= date('Y-m-10')) && ($check_last_month_cutoff * 2) > 1){

            $status  = 'error';
            $message = 'Employee had reached the limit for "'. ucfirst($request->leave_type) .' Leave" that they can file for the cut-off of '. date('F d, Y', strtotime($last_month_cutoff)) .' to '. date('F 10, Y') .'.';
        
        }else if( $request->leave_type == 'half-day' && ($request->date >= date('Y-m-11') && $request->date_to <= date('Y-m-25')) && ($check_current_month_cutoff * 2) > 1){

            $status  = 'error';
            $message = 'Employee had reached the limit for "'. ucfirst($request->leave_type) .' Leave" that they can file for the cut-off of '. date('F 11, Y') .' to '. date('F 25, Y') .'.';
        
        }else if( $request->leave_type == 'half-day' && ($request->date >= date('Y-m-26') && $request->date_to <= $next_month_cutoff) && ($check_next_month_cutoff * 2) > 1){

            $status  = 'error';
            $message = 'Employee had reached the limit for "'. ucfirst($request->leave_type) .' Leave" that they can file for the cut-off of '. date('F 26, Y') .' to '. date('F d, Y', strtotime($next_month_cutoff)) .'.';
        
        }else{
            
            DB::table('leave_details')->insert([
                'emp_no'        => $request->emp_no,
                'emp_name'      => $request->emp_name,
                'date'          => $request->date,
                'date_to'       => $request->date_to,
                'year_of_leave' => $year_of_leave,
                'leave_type'    => $request->leave_type,
                'no_of_day'     => $request->no_of_day,
                'reason'        => $request->reason,
                'encoder_id'    => $request->encoder_id,
                'encoded_by'    => $request->encoded_by,
                'created_at'    => now()
            ]);

            $status  = 'success';
            $message = 'Leave request has been successfully filed. Please check the "View Records" to know the status of the employee request';

        }

        return [
            'status'            => $status,
            'message'           => $message
        ];
    }

    // DELETE LEAVE REQUEST
    public function destroy(Request $request){

        $data = DB::table('leave_details')->where([['id', '=', $request->id]])->first();

        if( empty($data) ){

            $status  = 'error';
            $message = 'Failed to delete. Leave request may have been already deleted';
        
        }else{

            if($data->status == 'n'){

                DB::table('deleted_leave_records')->insert([
                    'emp_no'        => $data->emp_no,
                    'emp_name'      => $data->emp_name,
                    'leave_type'    => $data->leave_type,
                    'date'          => $data->date,
                    'date_to'       => $data->date_to,
                    'no_of_day'     => $data->no_of_day,
                    'year_of_leave' => $data->year_of_leave,
                    'reason'        => $data->reason,
                    'status'        => $data->status,
                    'encoder_id'    => $data->encoder_id,
                    'encoded_by'    => $data->encoded_by,
                    'date_encoded'  => $data->created_at,
                    'deleted_by'    => $request->deleted_by,
                    'created_at'    => now()
                ]);
        
                DB::table('leave_details')->delete(['id' => $request->id]);

                $status  = 'success';
                $message = 'Leave request has been successfully deleted';

            }else{

                $status  = 'error';
                $message = 'Failed to delete. Leave request already approved or disapproved';

            }
        
        }

        return [
            'status'            => $status,
            'message'           => $message
        ];

    }

    // RESTORE DELETED LEAVE REQUEST
    public function restore(Request $request){

        $data = DB::table('deleted_leave_records')->where([['id', '=', $request->id]])->first();

        if( empty($data) ){

            $status  = 'error';
            $message = 'Failed to restore. Leave request may have been already restored';
        
        }else{

            DB::table('leave_details')->insert([
                'emp_no'        => $data->emp_no,
                'emp_name'      => $data->emp_name,
                'leave_type'    => $data->leave_type,
                'date'          => $data->date,
                'date_to'       => $data->date_to,
                'no_of_day'     => $data->no_of_day,
                'year_of_leave' => $data->year_of_leave,
                'reason'        => $data->reason,
                'status'        => $data->status,
                'encoder_id'    => $data->encoder_id,
                'encoded_by'    => $data->encoded_by,
                'created_at'    => $data->created_at,
                'updated_at'    => now()
            ]);

            DB::table('deleted_leave_records')->delete(['id' => $request->id]);

            $status  = 'success';
            $message = 'Leave request has been successfully restored';
        
        }
        
        return [
            'status'            => $status,
            'message'           => $message
        ];

    }

    // APPROVE LEAVE REQUEST
    public function approve(Request $request){

        $data = DB::table('leave_details')->where([['id', '=', $request->id]])->first();

        if( empty($data) ){

            $status  = 'error';
            $message = 'Failed to approve. Leave request may have been deleted';
        
        }else{

            if ($data->leave_type != 'half-day'){
                $no_of_filed_leave = DB::table('leave_details')->where([
                    ['emp_no', '=', $data->emp_no],
                    ['year_of_leave', '=', date('Y')],
                    ['leave_type', '=', $data->leave_type],
                    ['status', '=', 'a']
                ])->sum('no_of_day');
            }else{
                $no_of_filed_leave = DB::table('leave_details')->where([
                    ['emp_no', '=', $data->emp_no],
                    ['date', '>=', date('Y-m-01')],
                    ['date_to', '<=', date('Y-m-31')],
                    ['leave_type', '=', 'half-day'],
                    ['status', '=', 'a']
                ])->sum('no_of_day');
    
                $last_month_cutoff = date('Y-m-26', strtotime(date('Y-m-d').' - 1 months'));
                $next_month_cutoff = date('Y-m-10', strtotime(date('Y-m-d').' + 1 months'));
    
                $check_last_month_cutoff = DB::table('leave_details')->where([
                    ['emp_no', '=', $data->emp_no],
                    ['date', '>=', $last_month_cutoff],
                    ['date_to', '<=', date('Y-m-10')],
                    ['leave_type', '=', 'half-day'],
                    ['status', '=', 'a']
                ])->sum('no_of_day');
    
                $check_current_month_cutoff = DB::table('leave_details')->where([
                    ['emp_no', '=', $data->emp_no],
                    ['date', '>=', date('Y-m-11')],
                    ['date_to', '<=', date('Y-m-25')],
                    ['leave_type', '=', 'half-day'],
                    ['status', '=', 'a']
                ])->sum('no_of_day');
    
                $check_next_month_cutoff = DB::table('leave_details')->where([
                    ['emp_no', '=', $data->emp_no],
                    ['date', '>=', date('Y-m-26')],
                    ['date_to', '<=', date($next_month_cutoff)],
                    ['leave_type', '=', 'half-day'],
                    ['status', '=', 'a']
                ])->sum('no_of_day');
            }
    
            if( $data->leave_type == 'sick' ){
                $max_leave = '10';
            }else if( $data->leave_type == 'vacation' ){
                $max_leave = '15';
            }else if( $data->leave_type == 'half-day' ){
                $max_leave = '2';
            }else{
                $max_leave = '5';
            }
            
            if( $data->leave_type != 'half-day' && ($no_of_filed_leave == $max_leave || $no_of_filed_leave + $data->no_of_day > $max_leave ) && $data->year_of_leave == date('Y') ){
    
                $status  = 'error';
                $message = 'Failed to approve. Employee had reached the limit of "'. ucfirst($data->leave_type) .' Leave" that they can file for this year.';
            
            }else if( $data->leave_type == 'half-day' && ($no_of_filed_leave * 2) == $max_leave && date('m', strtotime($data->date)) == date('m') ){
    
                $status  = 'error';
                $message = 'Failed to approve. Employee had reached the limit for "'. ucfirst($data->leave_type) .' Leave" that they can file for this month.';
            
            }else if( $data->leave_type == 'half-day' && ($data->date >= $last_month_cutoff && $data->date_to <= date('Y-m-10')) && ($check_last_month_cutoff * 2) > 1){
    
                $status  = 'error';
                $message = 'Failed to approve. Employee had reached the limit for "'. ucfirst($data->leave_type) .' Leave" that they can file for the cut-off of '. date('F d, Y', strtotime($last_month_cutoff)) .' to '. date('F 10, Y') .'.';
            
            }else if( $data->leave_type == 'half-day' && ($data->date >= date('Y-m-11') && $data->date_to <= date('Y-m-25')) && ($check_current_month_cutoff * 2) > 1){
    
                $status  = 'error';
                $message = 'Failed to approve. Employee had reached the limit for "'. ucfirst($data->leave_type) .' Leave" that they can file for the cut-off of '. date('F 11, Y') .' to '. date('F 25, Y') .'.';
            
            }else if( $data->leave_type == 'half-day' && ($data->date >= date('Y-m-26') && $data->date_to <= $next_month_cutoff) && ($check_next_month_cutoff * 2) > 1){
    
                $status  = 'error';
                $message = 'Failed to approve. Employee had reached the limit for "'. ucfirst($data->leave_type) .' Leave" that they can file for the cut-off of '. date('F 26, Y') .' to '. date('F d, Y', strtotime($next_month_cutoff)) .'.';
            
            }else{
    
                DB::table('leave_details')->where(
                    ['id' => $request->id]
                )->update([
                    'status'           => 'a',
                    'status_change_by' => $request->status_change_by,
                    'updated_at'       => now()
                ]);
    
                $status  = 'success';
                $message = 'Leave request has been successfully approved';
            
            }

        }

        return [
            'status'            => $status,
            'message'           => $message
        ];

    }

    // DISAPPROVE LEAVE REQUEST
    public function disapprove(Request $request){

        $data = DB::table('leave_details')->where([['id', '=', $request->id]])->first();

        if( empty($data) ){

            $status  = 'error';
            $message = 'Failed to disapprove. Leave request may have been deleted';

        }else{

            DB::table('leave_details')->where(
                ['id' => $request->id]
            )->update([
                'status'           => 'c',
                'status_change_by' => $request->status_change_by,
                'updated_at'       => now()
            ]);
    
            $status  = 'success';
            $message = 'Leave request has been successfully disapproved';

        }
        
        return [
            'status'            => $status,
            'message'           => $message
        ];

    }

}
