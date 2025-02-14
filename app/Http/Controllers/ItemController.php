<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Modal;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Exports\ItemsExport;
use App\Imports\ItemsImport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use RealRashid\SweetAlert\Facades\Alert;

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
        // dd($request->all);
        $query = Item::with(['category', 'warehouse']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('warehouse', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        return response()->json($query->paginate(5));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $warehouses = Warehouse::all();
        $suppliers = Supplier::all();
        $categories = Category::all();
        return view('manager.items.create', compact('warehouses', 'suppliers', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request)
    {
        $item = item::create([
            'name' => $request->name,
            'code' => $request->code,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'unit' => $request->unit,
            'stock' => 0,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'warehouse_id' => $request->warehouse_id,
        ]);

        $item->suppliers()->attach($request->suppliers);

        toast('Success Added', 'success');
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
        $suppliers = Supplier::all();
        $categories = Category::all();
        return view('manager.items.edit', compact('item', 'warehouses', 'selectedWarehouse', 'suppliers', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $item->update([
            'name' => $request->name,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'unit' => $request->unit,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'warehouse_id' => $request->warehouse_id,
        ]);

        if ($request->has('suppliers')) {
            $item->suppliers()->sync($request->suppliers);
        } else {
            $item->suppliers()->detach();
        }

        toast('Update Success', 'success');
        return redirect()->route('manager.items.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {

        $item->with('purchase')->where('item_id', $item)->delete();
        return redirect()->back();
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
