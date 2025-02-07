<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
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

        return view('manager.finance.purchase.index', compact('purchase_items'));
    }

    public function getItemsPurchase()
    {
        return response()->json(Item::select('id', 'name', 'price')->get());
    }

    public function getPurchaseItem(Request $request)
    {
        $query = Purchase::with('item');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', '%' . $search . '%')
                    ->orWhere('supplier_name', 'like', '%' . $search . '%')
                    ->orWhereHas('item', function ($query) use ($search) {
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
        return view('manager.finance.purchase.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request, Purchase $purchases)
    {

        // dd($request->all());
        DB::transaction(function () use ($request) {
        $validated = $request->validated();
        $validated['status'] = 'lunas';
        $validated['invoice_number'] = 'INV-' . now()->format('Y') . '/' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);

        $purchase =  Purchase::create($validated);

        if ($purchase) {
            $item = Item::find($request->item_id);
            $item->stock += $request->qty;
            $item->save();
        }
        });
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
        //
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
