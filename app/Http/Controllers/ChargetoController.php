<?php

namespace App\Http\Controllers;

use App\Models\Chargeto;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class ChargetoController extends Controller implements HasMiddleware
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
        return Chargeto::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            'payer_name' => 'required|string|unique:str_chargeto,payer_name',
            'branch' => 'required|string',
            'address' => 'nullable|string',
            'mobile_number' => 'required|string',
            'contact_person' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $chargeto = Chargeto::create($field);
        return ['chargeto' => $chargeto, 'message' => 'Charge To added successfully'];

    }

    /**
     * Display the specified resource.
     */
    public function show(chargeto $chargeto)
    {
        return [$chargeto];
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, chargeto $chargeto)
    {
        $field = $request->validate([
            'payer_name' => 'required|string',
            'branch' => 'required|string',
            'address' => 'nullable|string',
            'mobile_number' => 'required|string',
            'contact_person' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        $chargeto->update($field);

        return [$chargeto];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(chargeto $chargeto)
    {
        $chargeto->delete();
        return ['message' => 'Charge to was deleted!'];
    }


    public function chargeToBranch($branch = null)
    {
        $query = Chargeto::query();
        if (empty($branch)) {
            return response()->json([], 200);
        }

        $query->where('branch', $branch);

        $records = $query->get();
        return $records;
    }
}
