<?php

namespace App\Http\Controllers;

use App\Models\Subdepartment;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class SubdepartmentController extends Controller implements HasMiddleware
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
        $innerjoin = \DB::table('emp_department_sub')
            ->join('emp_department', 'emp_department_sub.department_header_id', '=', 'emp_department.id')
            ->get(['emp_department_sub.*', 'emp_department.department']);
        return $innerjoin;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            'department_header_id' => 'required',
            'department_sub' => 'required|string|unique:emp_department_sub,department_sub',
        ]);

        $subdepartment = Subdepartment::create($field);
        return ['sub_department' => $subdepartment, 'message' => 'Sub-Department added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(Subdepartment $subdepartment)
    {
        $id = $subdepartment->id;
        $subdepartment = \DB::table('emp_department_sub')
            ->join('emp_department', 'emp_department_sub.department_header_id', '=', 'emp_department.id')->where('emp_department_sub.id', "=", $id)
            ->get(['emp_department_sub.*', 'emp_department.department']);
        return $subdepartment;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subdepartment $subdepartment)
    {
        $field = $request->validate([
            'department_header_id' => 'required',
            'department_sub' => 'required',
        ]);

        $subdepartment->update($field);

        return [$subdepartment];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subdepartment $subdepartment)
    {
        $subdepartment->delete();
        return ['message' => 'Sub-Department was deleted!'];
    }
}
