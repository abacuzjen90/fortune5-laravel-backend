<?php

namespace App\Http\Controllers;

use App\Models\DateLimit;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class DateLimitController extends Controller implements HasMiddleware
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
        return DateLimit::all();
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
            'date' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $field['encoder'] = $request->user()->id;

        $datelimit = DateLimit::create($field);
        return ['datelimit' => $datelimit, 'message' => 'Date limit added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(DateLimit $dateLimit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DateLimit $dateLimit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DateLimit $dateLimit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DateLimit $dateLimit, $id)
    {
        $dateLimit = DateLimit::find($id);
        $dateLimit->delete();
        return ['message' => 'Date limit was deleted!'];
    }
}
