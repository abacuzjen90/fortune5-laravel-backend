<?php

namespace App\Http\Controllers;

use App\Models\RentalTenant;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RentalTenantController extends Controller implements HasMiddleware
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
        return RentalTenant::all();
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'email_address' => 'nullable|string',
        ]);

        $tenant = RentalTenant::create($field);
        return ['tenant' => $tenant, 'message' => 'Tenant added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(RentalTenant $rentalTenant, $id)
    {
        $rentalTenant = RentalTenant::find($id);
        return [$rentalTenant];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RentalTenant $rentalTenant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RentalTenant $rentalTenant, $id)
    {
        $item = RentalTenant::findOrFail($id);
        $field = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'address' => 'nullable|string',
            'contact_number' => 'nullable|string',
            'email_address' => 'nullable|string',
        ]);

        $item->update($field);
        return ['rentaltenant' => $rentalTenant, 'message' => 'Tenant updated successfully'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RentalTenant $rentalTenant, $id)
    {
        $rentalTenant = RentalTenant::find($id);
        $rentalTenant->delete();
        return ['message' => 'Tenant was deleted!'];
    }
}
