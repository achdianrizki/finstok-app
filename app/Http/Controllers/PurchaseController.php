<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePurchaseRequest;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $purchase_items = Purchase::with('item')->get();

        return view('manager.finance.purchase.index', compact('purchase_items'));
    }
    
    public function getPurchaseItem(Request $request)
    {
        $query = Purchase::with('items');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
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
        DB::transaction(function () use ($request){
            $validated = $request->validated();
            
            $validated['purchase_type'] = 'asset';
            $validated['total_price'] = str_replace('.', '', $request->total_price);
            
            // dd($validated);

            Purchase::create($validated);
        });        

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
