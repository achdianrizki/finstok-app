<?php

namespace App\Http\Controllers;

use App\Exports\ItemsExport;
use App\Models\Item;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Imports\ItemsImport;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with(['category', 'warehouse'])->get();

        return view('manager.items.index', compact('items'));
    }
    public function getItems(Request $request)
    {
        $query = Item::with(['category', 'warehouse']);

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
                $q->orWhereHas('category', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
                $q->orWhereHas('warehouse', function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%');
                });
            });
        }
        $products = $query->paginate(5);

        return response()->json($products);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = Warehouse::all();
        return view('manager.items.create', compact('warehouses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request, Item $items)
    {
        DB::transaction(function () use ($request, $items) {
            $validated =  $request->validated();

            $items = Item::create($validated);

            Purchase::create([
                'name' => $validated['name'],
                'item_id' => $items->id,
                'price' => $validated['price'],
                'total_price' => $validated['price'] * $validated['stok'],
                'purchase_type' => 'stock',
                'supplier_name' => "pak asep",
            ]);
        });

        return redirect()->route('manager.items.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {   
        $warehouses = Warehouse::all();
        $selectedWarehouse = $item->warehouse_id;
        return view('manager.items.edit', compact('item', 'warehouses', 'selectedWarehouse'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        DB::transaction(function () use ($request, $item) {
            $validated =  $request->validated();

            $item->update($validated);

            Purchase::where('id', $item->id)->update([
                'name' => $validated['name'],
                'item_id' => $item->id,
                'price' => $validated['price'],
                'total_price' => $validated['price'] * $validated['stok'],
                'purchase_type' => 'stock',
                'supplier_name' => "pak asep",
            ]);
        });

        return redirect()->route('manager.items.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }

    public function exportPDF()
    {
        $items = Item::all();
        $pdf = Pdf::loadView('exports.items.pdf', compact('items'));
        return $pdf->download('item.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new ItemsExport, 'products.xlsx');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv'
        ]);

        Excel::import(new ItemsImport, $request->file('file'));

        return redirect()->back()->with('success', 'Items Imported Successfully');
    }
}
