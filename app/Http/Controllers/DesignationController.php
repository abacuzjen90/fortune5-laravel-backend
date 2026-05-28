<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class DesignationController extends Controller implements HasMiddleware
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
        return Designation::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            'designation' => 'required|string|unique:emp_designation,designation',
        ]);

        $designation = Designation::create($field);
        return ['designation' => $designation, 'message' => 'Designation added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(Designation $designation)
    {
        return [$designation];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Designation $designation)
    {
        $field = $request->validate([
            'designation' => 'required|string',
        ]);

        $designation->update($field);

        return [$designation];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        $designation->delete();
        return ['message' => 'Designation was deleted!'];
    }
}
