<?php

namespace App\Http\Controllers;

use App\Models\GeneralSalesMonitoring;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Carbon\Carbon;

class GeneralSalesMonitoringController extends Controller implements HasMiddleware
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
    public function index(Request $request)
    {
        $date = $request->query('date'); // optional ?date=11/08/2025

        $query = GeneralSalesMonitoring::query();

        if ($date) {
            $query->where('date', $date);
        }
            $query->where('encoder', $request->user()->id);

        return response()->json($query->get());
    }


    public function getMonitoringHistory(Request $request)
    {
        //$date = $request->query('date');        // ?date=11/08/2025
        $date = $request->input('date');

        $query = GeneralSalesMonitoring::query()
            ->join('users', 'users.id', '=', 'general_sales_monitorings.encoder')
            ->select(
                'general_sales_monitorings.*',
                'users.name as encoder_name',
            );

        if ($date) {
            $query->where('general_sales_monitorings.date', $date);
        }

        return response()->json($query->get());
    }


     public function getMonitoringEncoder(Request $request)
    {
        $date = $request->input('date');
        $encoder = $request->input('encoder');

        $query = GeneralSalesMonitoring::query();

        if ($date) {
            $query->where('date', $date);
        }
            $query->where('encoder', $encoder);

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
            'type' => 'required|string',
            'referenceno' => 'required|string',
            'name' => 'required|string',
            'description' => 'required|string',
            'mode_of_payment' => 'required|string',
            'gcash_referenceno' => 'nullable|string',
            'bank' => 'nullable|string',
            'bank_date' => 'nullable|string',
            'checkno' => 'nullable|string',
            'receiving_bank' => 'nullable|string',
            'bank_transfer_refno' => 'nullable|string',
            'amount' => 'required|numeric',
        ]);

        $field['encoder'] = $request->user()->id;

        $monitoring = GeneralSalesMonitoring::create($field);
        return ['monitoring' => $monitoring, 'message' => 'Monitoring added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(GeneralSalesMonitoring $generalSalesMonitoring)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GeneralSalesMonitoring $generalSalesMonitoring)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GeneralSalesMonitoring $generalSalesMonitoring)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GeneralSalesMonitoring $generalSalesMonitoring, $id)
    {
        $monitoring = GeneralSalesMonitoring::findOrFail($id);
        $monitoring->delete();
        return ['message' => 'Transaction was deleted!'];
    }


    public function transactionSearchResult(Request $request)
    {
        $keyword = $request->query('keyword');

        if (empty($keyword)) {
            return response()->json([
                'records' => [],
                'count'   => 0,
            ]);
        }

        $query = GeneralSalesMonitoring::query()
             ->leftJoin('users', function($join) {
            $join->on(\DB::raw('CAST(general_sales_monitorings.encoder AS UNSIGNED)'), '=', 'users.id');
        })
        ->select(
            'general_sales_monitorings.*',
            'users.name as encoder_name'
        );

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('general_sales_monitorings.name', 'LIKE', "%{$keyword}%")
                ->orWhere('general_sales_monitorings.referenceno', 'LIKE', "%{$keyword}%")
                ->orWhere('general_sales_monitorings.checkno', 'LIKE', "%{$keyword}%")
                ->orWhere('general_sales_monitorings.gcash_referenceno', 'LIKE', "%{$keyword}%")
                ->orWhere('general_sales_monitorings.description', 'LIKE', "%{$keyword}%")
                ->orWhere('general_sales_monitorings.bank', 'LIKE', "%{$keyword}%")
                ->orWhere('general_sales_monitorings.receiving_bank', 'LIKE', "%{$keyword}%")
                ->orWhere('general_sales_monitorings.bank_transfer_refno', 'LIKE', "%{$keyword}%");
            });
        }

        $records = $query->orderBy('general_sales_monitorings.created_at', 'desc')->get();

        return response()->json([
            'records' => $records,
            'count'   => $records->count(),
        ]);
    }


}
