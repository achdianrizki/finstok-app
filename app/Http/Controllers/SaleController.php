<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreSaleRequest;
use App\Models\Buyer;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('manager.finance.sales.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {   
        $buyers = Buyer::create([
            'name' => $request->buyer_name,
            'address' => $request->address,
            'phone' => $request->phone
        ]);


        $sales = Sale::create([

        ]);
        
        return view('manager.finance.sales.create', compact('items', 'distributors'));
    }

    public function searchItem(Request $request)
    {
        $query = Item::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        $items = $query->get();
        return response()->json(['data' => $items]);
    }


    public function getSaleItem(Request $request)
    {
        $items = Item::where('id', $request->item_id)->get();
        echo "
            <td>'$items->id'</td>
            <td>'$items->name'</td>
        ";
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        DB::transaction(function () use ($request) {});
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
