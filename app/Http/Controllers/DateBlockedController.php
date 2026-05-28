<?php

namespace App\Http\Controllers;

use App\Models\DateBlocked;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class DateBlockedController extends Controller implements HasMiddleware
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
        return DateBlocked::all();
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
        ]);

        $field['encoder'] = $request->user()->id;

        $dateblocked = DateBlocked::create($field);
        return ['dateblocked' => $dateblocked, 'message' => 'Date blocked added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(DateBlocked $dateBlocked)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DateBlocked $dateBlocked)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DateBlocked $dateBlocked)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DateBlocked $dateBlocked, $id)
    {
        $dateBlocked = DateBlocked::find($id);
        $dateBlocked->delete();
        return ['message' => 'Date blocked was deleted!'];
    }
}
