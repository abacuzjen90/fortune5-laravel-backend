<?php

namespace App\Http\Controllers;

use App\Models\RentalSpace;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RentalSpaceController extends Controller implements HasMiddleware
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
        return RentalSpace::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            'property_name' => 'required|string',
            'unit_number' => 'nullable|string',
            'type' => 'nullable|string',
            'address' => 'nullable|string',
            'monthly_rent' => 'nullable|numeric',
            'status' => 'nullable|string',
        ]);

        $rental = RentalSpace::create($field);
        return ['rental' => $rental, 'message' => 'Rental added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(RentalSpace $rentalSpace, $id)
    {
        $rentalSpace = RentalSpace::find($id);
        return [$rentalSpace];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RentalSpace $rentalSpace)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RentalSpace $rentalSpace, $id)
    {
        $item = RentalSpace::findOrFail($id);
        $field = $request->validate([
            'property_name' => 'required|string',
            'unit_number' => 'nullable|string',
            'type' => 'nullable|string',
            'address' => 'nullable|string',
            'monthly_rent' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $item->update($field);
        return ['rentalspace' => $rentalSpace, 'message' => 'Rental updated successfully'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RentalSpace $rentalSpace, $id)
    {
        $rentalSpace = RentalSpace::find($id);
        $rentalSpace->delete();
        return ['message' => 'Rental was deleted!'];
    }
}
