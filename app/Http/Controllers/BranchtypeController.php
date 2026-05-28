<?php

namespace App\Http\Controllers;

use App\Models\Branchtype;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;


class BranchtypeController extends Controller implements HasMiddleware
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
        return Branchtype::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $field = $request->validate([
            'type' => 'required|string|unique:str_branchtype,type',
            'description' => 'nullable',
        ]);

        $branchtype = Branchtype::create($field);
        return ['branchtype' => $branchtype, 'message' => 'Branch Type added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(Branchtype $branchtype)
    {
        return [$branchtype];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branchtype $branchtype)
    {
        $field = $request->validate([
            'type' => 'required',
            'description' => 'nullable',
        ]);

        $branchtype->update($field);

        return [$branchtype];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branchtype $branchtype)
    {
        $branchtype->delete();
        return ['message' => 'Branch type was deleted!'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
