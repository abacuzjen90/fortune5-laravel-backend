<?php

namespace App\Http\Controllers;

use App\Models\RentalExpenses;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RentalExpensesController extends Controller implements HasMiddleware
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
        $query = RentalExpenses::query()
            ->join('rental_spaces', 'rental_spaces.id', '=', 'rental_expenses.property')
            ->select(
                'rental_expenses.*',
                         'rental_spaces.property_name',
                         'rental_spaces.unit_number',
            )
            ->orderBy('rental_expenses.id', 'desc');

        return response()->json($query->get());
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
            'property' => 'required|string',
            'category' => 'required|string',
            'amount' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $expenses = RentalExpenses::create($field);
        return ['expenses' => $expenses, 'message' => 'Expenses added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(RentalExpenses $rentalExpenses, $id)
    {
        $query = RentalExpenses::query()
            ->join('rental_spaces', 'rental_spaces.id', '=', 'rental_expenses.property')
            ->select(
                'rental_expenses.*',
                         'rental_spaces.property_name',
                         'rental_spaces.unit_number',
            );
        $query->where('rental_expenses.id', $id);
        return response()->json($query->get());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RentalExpenses $rentalExpenses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RentalExpenses $rentalExpenses, $id)
    {
        $item = RentalExpenses::findOrFail($id);
        $field = $request->validate([
            'date' => 'required|string',
            'property' => 'required|string',
            'category' => 'required|string',
            'amount' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        $item->update($field);
        return ['rentalexpenses' => $rentalExpenses, 'message' => 'Expenses updated successfully'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RentalExpenses $rentalExpenses, $id)
    {
        $rentalExpenses = RentalExpenses::find($id);
        $rentalExpenses->delete();
        return ['message' => 'Expenses was deleted!'];
    }
}
