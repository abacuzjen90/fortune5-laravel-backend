<?php

namespace App\Http\Controllers;

use App\Models\InventoryBooklet;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class InventoryBookletController extends Controller implements HasMiddleware
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
        return InventoryBooklet::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            'form_name' => 'required|string',
            'form_description' => 'required|string',
        ]);

        $inventoryBooklet = InventoryBooklet::create($field);
        return ['booklet' => $inventoryBooklet, 'message' => 'Booklet added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryBooklet $inventoryBooklet, $id)
    {
        $inventoryBooklet = InventoryBooklet::find($id);
        return [$inventoryBooklet];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryBooklet $inventoryBooklet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryBooklet $inventoryBooklet)
    {
        $inventoryBooklet->delete();
        return ['message' => 'Booklet was deleted!'];
    }
}
