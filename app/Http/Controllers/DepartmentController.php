<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class DepartmentController extends Controller implements HasMiddleware
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
        return Department::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            'department' => 'required|string|unique:emp_department,department',
        ]);

        $department = Department::create($field);
        return ['department' => $department, 'message' => 'Department added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return [$department];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $field = $request->validate([
            'department' => 'required',
        ]);

        $department->update($field);

        return [$department];
    }

    public function deptdata()
    {
        $res = \DB::table('emp_department')
            ->join('emp_department_sub', 'emp_department.id', '=', 'emp_department_sub.department_header_id')
            ->get(['emp_department.department', 'emp_department_sub.department_sub']);
        return $res;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return ['message' => 'Department was deleted!'];
    }
}
