<?php

namespace App\Http\Controllers;

use App\Models\RentalLease;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;

class RentalLeaseController extends Controller implements HasMiddleware
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
        $query = RentalLease::query()
            ->join('rental_tenants', 'rental_tenants.id', '=', 'rental_leases.tenant_id')
            ->join('rental_spaces', 'rental_spaces.id', '=', 'rental_leases.unit_id')
            ->select(
                'rental_leases.id',
                'rental_leases.unit_id',
                'rental_leases.tenant_id',
                'rental_leases.start_date',
                'rental_leases.end_date',
                'rental_leases.monthly_rent',
                'rental_leases.deposit',
                'rental_leases.reference_number',
                'rental_leases.notes',
                'rental_leases.created_at',
                'rental_tenants.first_name',
                'rental_tenants.last_name',
                'rental_spaces.property_name',
                'rental_spaces.unit_number',

            )->orderBy('rental_leases.id', 'desc');

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
            'unit_id' => 'required|numeric',
            'tenant_id' => 'required|numeric',
            'start_date' => 'nullable|string',
            'end_date' => 'nullable|string',
            'monthly_rent' => 'nullable|numeric',
            'deposit' => 'nullable|numeric',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $lease = RentalLease::create($field);
        return ['lease' => $lease, 'message' => 'Lease added successfully'];
    }

    /**
     * Display the specified resource.
     */
    public function show(RentalLease $rentalLease, $id)
    {
        $query = RentalLease::query()
            ->join('rental_tenants', 'rental_tenants.id', '=', 'rental_leases.tenant_id')
            ->join('rental_spaces', 'rental_spaces.id', '=', 'rental_leases.unit_id')
            ->select(
                'rental_leases.*',
                'rental_tenants.*',
                'rental_spaces.*',
                'rental_leases.monthly_rent as monthly_rent',
                'rental_leases.id as leaseid',
            );
        $query->where('rental_leases.id', $id);
        return response()->json($query->get());
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RentalLease $rentalLease)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RentalLease $rentalLease, $id)
    {
        $item = RentalLease::findOrFail($id);
        $field = $request->validate([
            'unit_id' => 'required|numeric',
            'tenant_id' => 'required|numeric',
            'start_date' => 'nullable|string',
            'end_date' => 'nullable|string',
            'monthly_rent' => 'nullable|numeric',
            'deposit' => 'nullable|numeric',
            'reference_number' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);


        $rentalLease = $item->update($field);
        return ['rentallease' => $item, 'message' => 'Lease updated successfully'];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RentalLease $rentalLease, $id)
    {
        $rentalLease = RentalLease::find($id);
        $rentalLease->delete();
        return ['message' => 'Lease was deleted!'];
    }


    public function rentalTransactionResult(Request $request)
    {
        $year  = $request->query('year');
        $month  = $request->query('month');
        $unitId   = $request->query('unit_id');
        $tenantId = $request->query('tenant_id');

        if (empty($year) && empty($month) && empty($unitId) && empty($tenantId)) {
            return response()->json([
                'records' => [],
                'count'   => 0,
            ]);
        }

        $query = RentalLease::query()
            ->leftJoin('rental_spaces', 'rental_leases.unit_id', '=', 'rental_spaces.id')
            ->leftJoin('rental_tenants', function ($join) {
            $join->on(
                \DB::raw('CAST(rental_leases.tenant_id AS UNSIGNED)'),
                '=',
                'rental_tenants.id'
             );
            })
            ->select(
                'rental_leases.id',
                'rental_leases.start_date',
                'rental_leases.end_date',
                'rental_leases.monthly_rent',
                'rental_leases.deposit',
                'rental_leases.reference_number',
                'rental_leases.notes',
                'rental_leases.created_at',
                'rental_tenants.first_name',
                'rental_tenants.last_name',
                'rental_tenants.address as tenant_address',
                'rental_tenants.contact_number',
                'rental_tenants.email_address',
                'rental_spaces.property_name',
                'rental_spaces.unit_number',
                'rental_spaces.type',
                'rental_spaces.address as spaces_address',
                'rental_spaces.status',

            )->orderBy('rental_leases.created_at', 'desc');

        // Filter by month
        if (!empty($month)) {
            $query->where('rental_leases.start_date', $month);
        }

        //filter by year
        if (!empty($year)) {
            $query->where('rental_leases.start_date', 'like', '% ' . $year);
        }

        // Filter by unit
        if (!empty($unitId)) {
            $query->where('rental_leases.unit_id', $unitId);
        }

        // Filter by tenant
        if (!empty($tenantId)) {
            $query->where('rental_leases.tenant_id', $tenantId);
        }

        $records = $query
            ->orderBy('rental_leases.created_at', 'desc')
            ->get();

        return response()->json([
            'records' => $records,
            'count'   => $records->count(),
        ]);
    }

}
