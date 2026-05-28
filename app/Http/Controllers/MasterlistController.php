<?php

namespace App\Http\Controllers;

use App\Models\Masterlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Carbon\Carbon;

class MasterlistController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Masterlist::all();
    }

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        $field = $request->validate([
            'branch' => 'required',
            'department' => 'required',
            'designation' => 'required',
            'salary_type' => 'required',
            'first_name' => 'required|max:255',
            'middle_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'dateofbirth' => 'required|date_format:"Y-m-d"',
            'age' => 'required|integer|min:0',
            'gender' => 'required',
            'contact_number' => 'required',
            'pagibig' => 'required',
            'sss' => 'required',
            'philhealth' => 'required',
            'tin' => 'required',
            'basic_pay' => 'required|numeric',
            'cola' => 'required|numeric',
            'employment_status' => 'required',
            'datehired' => 'required|date_format:"Y-m-d"',
            'dateregularized' => 'required|date_format:"Y-m-d"',
            'remarks' => 'nullable',
            'employee_type' => 'required',
        ]);

        // $masterlist = $request->user()->masterlists()->create($field);
        // return [$masterlist];

        $masterlist = Masterlist::create($field);
        return ['masterlist' => $masterlist, 'message' => 'Employee added successfully'];

        // return ['masterlist' => $masterlist, 'user' => $masterlist->user];

    }

    /**
     * Display the specified resource.
     */
    public function show(Masterlist $masterlist)
    {
        return [$masterlist];
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Masterlist $masterlist)
    {
        // Gate::authorize('modify', $masterlist);

        $field = $request->validate([
            'branch' => 'required',
            'department' => 'required',
            'designation' => 'required',
            'salary_type' => 'required',
            'first_name' => 'required|max:255',
            'middle_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'dateofbirth' => 'required',
            'age' => 'required|integer|min:0',
            'gender' => 'required',
            'contact_number' => 'required',
            'pagibig' => 'required',
            'sss' => 'required',
            'philhealth' => 'required',
            'tin' => 'required',
            'basic_pay' => 'required|numeric',
            'cola' => 'required|numeric',
            'employment_status' => 'required',
            'datehired' => 'required',
            'dateregularized' => 'required',
            'remarks' => 'nullable',
            'employee_type' => 'required',
            'pag_ibig_prem' => 'nullable|numeric|min:0',
            'cash_loan' => 'nullable|numeric|min:0',
            'cash_bond' => 'nullable|numeric|min:0',
            'sss_loan' => 'nullable|numeric|min:0',
            'mp2' => 'nullable|numeric|min:0',
            'emp_liab' => 'nullable|numeric|min:0',
            'health_card' => 'nullable|numeric|min:0',
            'sss_calamity' => 'nullable|numeric|min:0',
            'sss_lrp' => 'nullable|numeric|min:0',
            'hdmf_loan' => 'nullable|numeric|min:0',
            'calamity' => 'nullable|numeric|min:0',

            'cash_loan_amount' => 'nullable',
            'cash_bond_amount' => 'nullable',
            'sss_loan_amount' => 'nullable',
            'mp2_amount' => 'nullable',
            'hdmf_loan_amount' => 'nullable',
            'sss_calamity_amount' => 'nullable',
            'health_card_amount' => 'nullable',
            'sss_lrp_amount' => 'nullable',
            'calamity_amount' => 'nullable',
            'cash_loan_term' => 'nullable',
            'cash_bond_term' => 'nullable',
            'sss_loan_term' => 'nullable',
            'mp2_term' => 'nullable',
            'hdmf_loan_term' => 'nullable',
            'sss_calamity_term' => 'nullable',
            'health_card_term' => 'nullable',
            'sss_lrp_term' => 'nullable',
            'calamity_term' => 'nullable',
        ]);

        $masterlist->update($field);

        return [$masterlist];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Masterlist $masterlist)
    {
        // Gate::authorize('modify', $masterlist);
        $masterlist->delete();
        return ['message' => 'The employee was deleted!'];
    }


    //Upload Image ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    public function uploadimage(Request $request)
    {
        $file = $request->file('photo');
        $originalName = $file->getClientOriginalName();
        $request->file('photo')->storeAs('images', $originalName, 'public');

        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png,gif|max:5120',
            'masterlist_id' => 'nullable',
            'encoder' => 'nullable',
            'encoded' => 'nullable',
            'profile_image' => 'nullable',
        ]);

        $res = \DB::table('emp_masterlist_image')->insert([
            'photo' => basename($originalName),
            'masterlist_id' => $request->masterlist_id,
            'encoder' => $request->encoder,
            'encoded' => $request->encoded,
            'profile_image' => $request->profile_image,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        return [$res];
    }

    public function showimage(Request $request)
    {
        $innerjoin = \DB::table('emp_masterlist_image')
        ->join('emp_masterlist', 'emp_masterlist_image.masterlist_id', '=', 'emp_masterlist.id')
        ->where('emp_masterlist.id', "=", $request->id)
        ->get(['emp_masterlist_image.*']);
        return $innerjoin;
    }

    public function showprofileimage(Request $request)
    {
        $innerjoin = \DB::table('emp_masterlist_image')
        ->join('emp_masterlist', 'emp_masterlist_image.masterlist_id', '=', 'emp_masterlist.id')
        ->where('emp_masterlist.id', "=", $request->id)
        ->where('emp_masterlist_image.profile_image', '=', 'y')
        ->limit(1)
        ->get(['emp_masterlist_image.*']);
        return $innerjoin;
    }

    public function unprofileimage(Request $request)
    {
        $updateall = \DB::table('emp_masterlist_image')
        ->where('masterlist_id', '=', $request->id)
        ->where('profile_image', '=', 'y')
        ->update(['profile_image' => 'n', 'updated_at' => Carbon::now()]);
        return $updateall;
    }
    public function toprofileimage($id)
    {
        $update = \DB::table('emp_masterlist_image')
        ->where('id', '=', $id)
        ->update(['profile_image' => 'y', 'updated_at' => Carbon::now()]);
        return  $update;
    }

    public function deleteimage($id)
    {
    // $imagePath = public_path('storage/images/imagename'); // Adjust the path as necessary
    // // Check if the image file exists and delete it
    // if (\File::exists($imagePath)) {
    //     \File::delete($imagePath);
    // }

         \DB::table('emp_masterlist_image')->where('id', '=', $id)->delete();
         return ['message' => 'The image was deleted!'];
    }
    //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
