<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouses = Warehouse::orderBy('name')->paginate(5);
        return view('manager.warehouse.index', compact('warehouses'));
    }

    public function getWarehouses(Request $request)
    {
        $query = Warehouse::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $warehouses = $query->paginate(5);

        return response()->json($warehouses);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manager.warehouse.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWarehouseRequest $request)
    {
        $validatedData = $request->validated();

        Warehouse::create($validatedData);

        return redirect()->route('manager.warehouses.index')->with('success','Warehouse added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        return view('manager.warehouse.edit', compact('warehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWarehouseRequest $request, Warehouse $warehouse)
    {
        $validatedData = $request->validated();
        
        $warehouse->update($validatedData);

        return redirect()->route('manager.warehouses.index')->with('success','Warehouse updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('manager.warehouses.index')->with('success','Warehouse deleted successfully');
    }
}
