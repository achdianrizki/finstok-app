<?php

namespace App\Http\Controllers;

use App\Models\Item;
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
        $query = Warehouse::query()->latest();

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

        toast('Gudang berhasil ditambahkan', 'success');
        return redirect()->route('manager.warehouses.index')->with('success', 'Gudang berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        return view('manager.warehouse.warehouse', compact('warehouse'));
    }

    public function getItemsByWarehouse(Request $request, Warehouse $warehouse)
    {
        $query = Item::with(['category', 'item_warehouse'])
            ->whereHas('item_warehouse', function ($q) use ($warehouse) {
                $q->where('slug', $warehouse->slug);
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        return response()->json($query->paginate(10));
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

        toast('Gudang berhasil diupdate', 'success');
        return redirect()->route('manager.warehouses.index')->with('success', 'Gudang berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        toast('Gudang berhasil dihapus', 'success');
        return redirect()->route('manager.warehouses.index')->with('success', 'Gudang berhasil dihapus');
    }
}
