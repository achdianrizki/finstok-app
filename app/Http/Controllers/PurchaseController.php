<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Modal;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StorePurchaseRequest;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchase_items = Purchase::with(['item'])->get();

        return view('manager.purchase.index', compact('purchase_items'));
    }

    public function getItemsPurchase()
    {
        return response()->json(Item::select('id', 'name', 'price')->get());
    }

    public function getPurchaseItem(Request $request)
    {
        $query = Purchase::with(['supplier']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('purchase_number', 'like', '%' . $search . '%')
                    ->orWhereHas('supplier', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('purchase_date', 'like', '%' . $search . '%');
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
        $suppliers = Supplier::all();
        $items = Item::all();
        return view('manager.purchase.create', compact('suppliers', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // dd($request);

        $total_qty = array_sum($request->qty);

        $purchase = Purchase::create([
            'purchase_number' => $request->purchase_number,
            'purchase_date' => $request->purchase_date,
            'supplier_id' => $request->supplier_id,
            'tax' => $request->tax,
            'information' => $request->information,
            'sub_total' => $request->sub_total,
            'total_discount' => $request->total_discount,
            'total_price' => $request->total_price,
            'total_qty' => $total_qty,
            'tax_type' => $request->taxt_type
        ]);

        foreach ($request->items as $index => $item_id) {
            $item = Item::findOrFail($item_id);
            $item->stock += $request->qty[$index];
            $item->save();

            $item->stock += $request->qty[$index];
            $item->save();

            $item->purchases()->attach($purchase->id, [
                'qty' => $request->qty[$index],
                'price_per_item' => $request->price_per_item[$index]
            ]);
        }


        toast('Data berhasil disimpan', 'success');
        return redirect()->route('manager.purchase.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $purchase->load('supplier');
        $warehouses = Warehouse::all();
        $suppliers = Supplier::all();
        $categories = Category::all();
        $items = Item::all();

        return view('manager.purchase.edit', compact('purchase', 'warehouses', 'suppliers', 'categories', 'items'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
