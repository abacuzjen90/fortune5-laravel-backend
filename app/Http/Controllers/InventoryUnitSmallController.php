<?php

namespace App\Http\Controllers;

use App\Models\Inventory_Unit_Small;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class InventoryUnitSmallController extends Controller implements HasMiddleware
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
        return Inventory_Unit_Small::all();
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory_Unit_Small $inventory_Unit_Small)
    {
        return [$inventory_Unit_Small];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventory_Unit_Small $inventory_Unit_Small)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventory_Unit_Small $inventory_Unit_Small)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventory_Unit_Small $inventory_Unit_Small)
    {
        //
    }
}
