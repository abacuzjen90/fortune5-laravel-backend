<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class DestinationController extends Controller implements HasMiddleware
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
        return Destination::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            'destination' => 'required|string|unique:str_destination,destination',
            'rate_cbm' => 'nullable|numeric',
            'rate_kilo' => 'nullable|numeric',
            'value_charge' => 'nullable|numeric',
            'minimum' => 'nullable|numeric',
            'advalorem' => 'nullable|numeric',
        ]);

        $field['encoder'] = $request->user()->name;

        $destination = Destination::create($field);
        return ['destination' => $destination, 'message' => 'Destination added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination)
    {
        return [$destination];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Destination $destination)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Destination $destination)
    {
        $field = $request->validate([
            'destination' => 'required|string',
            'rate_cbm' => 'nullable|numeric',
            'rate_kilo' => 'nullable|numeric',
            'value_charge' => 'nullable|numeric',
            'minimum' => 'nullable|numeric',
            'advalorem' => 'nullable|numeric',
        ]);

        $field['encoder'] = $request->user()->name;

        $destination->update($field);

        return ['destination' => $destination, 'message' => 'Destination updated successfully'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Destination $destination)
    {
        $destination->delete();
        return ['message' => 'Destination was deleted!'];
    }

    public function destinationRates($desti = null)
    {
        $query = Destination::query();
        if (empty($desti)) {
            return response()->json([], 200);
        }

        $query->where('destination', $desti);

        $records = $query->get();
        return $records;
    }
}
