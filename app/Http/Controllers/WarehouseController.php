<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function adjustStock(Request $request)
    {
        $itemWarehouse = DB::table('item_warehouse')
            ->where('item_id', $request->item_id)
            ->where('warehouse_id', $request->warehouse_id)
            ->update([
                'physical' => $request->physical,
                'difference' => $request->difference,
                'profit' => $request->profit,
                'updated_at' => now()
            ]);

        if ($itemWarehouse) {
            return response()->json(['message' => 'Stock adjusted successfully'], 200);
        }

        return response()->json(['message' => 'Item not found in warehouse'], 404);
    }

    public function getSalesItem($item_id, $warehouse_id)
    {
        $item = Item::whereHas('item_warehouse', function ($query) use ($warehouse_id) {
            $query->where('warehouse_id', $warehouse_id);
        })
            ->with(['item_warehouse' => function ($query) use ($warehouse_id) {
                $query->where('warehouse_id', $warehouse_id);
            }])
            ->find($item_id);

        if (!$item || $item->item_warehouse->isEmpty()) {
            return response()->json(['error' => 'Item not found in selected warehouse'], 404);
        }

        $warehouseData = $item->item_warehouse->first();

        return response()->json([
            'item_id' => $item->id,
            'code' => $item->code,
            'name' => $item->name,
            'stock' => $warehouseData->pivot->stock,
            'unit' => $item->unit,
            'price' => $warehouseData->pivot->price_per_item,
            'purchase_price' => $warehouseData->pivot->price_per_item,
            'discount1' => $warehouseData->pivot->discount1 ?? 0,
            'discount2' => $warehouseData->pivot->discount2 ?? 0,
        ]);
    }

    public function getItemsByWarehouses(Request $request)
    {
        $items = DB::table('item_warehouse')
            ->where('warehouse_id', $request->warehouse_id)
            ->join('items', 'items.id', '=', 'item_warehouse.item_id')
            ->select('items.id', 'items.name', 'item_warehouse.stock')
            ->get();

        return response()->json($items);
    }
}
