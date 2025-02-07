<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use App\Models\Buyer;
use App\Models\Distributor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;

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
        // $buyers = Buyer::create([
        //     'name' => $request->buyer_name,
        //     'address' => $request->address,
        //     'phone' => $request->phone
        // ]);


        // $sales = Sale::create([

        // ]);

        // SELECT ITEM
        $items = Item::all();
        $distributors = Distributor::all();


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


    public function getSales(Request $request)
    {
        // $items = Item::where('id', $request->item_id)->get();
        // echo "
        //     <td>'$items->id'</td>
        //     <td>'$items->name'</td>
        // ";

        // $query = Sale::query();

        // if ($request->has('search')) {
        //     $query->where('name', 'like', '%' . $request->search . '%');
        // }

        $sales = Sale::with(['item', 'distributor'])
            ->paginate(10);

        return response()->json($sales);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSaleRequest $request)
    {
        // DB::transaction(function () use ($request) {

        // });
        $validatedData = $request->validated();

        // Hapus format mata uang dari total_price dan down_payment sebelum perhitungan
        $validatedData['total_price'] = (float) preg_replace('/[^0-9]/', '', $validatedData['total_price']);
        $validatedData['down_payment'] = (float) preg_replace('/[^0-9]/', '', $validatedData['down_payment']);


        // Hitung remaining_payment setelah nilai yang benar diperoleh
        $validatedData['remaining_payment'] = $validatedData['total_price'] - $validatedData['down_payment'];

        Sale::create($validatedData);

        $item = Item::find($validatedData['item_id']);
        if ($item) {
            $item->decrement('stock', $validatedData['qty_sold']); // Mengurangi stok sebesar qty_sold
        }

        return redirect()->route('manager.sales.index')->with('success', 'Sale added successfully');
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
        // return view('manager.finance.sales', compact('sale'));
        return view('manager.finance.sales.edit', compact('sale'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        $validatedData = $request->validated();

        $additionalPayment = $validatedData['update_payment'];

        $newDownPayment = $sale->down_payment + $additionalPayment;

        $newRemainingPayment = max($sale->remaining_payment - $additionalPayment, 0);

        $paymentStatus = ($newDownPayment >= $sale->total_price) ? 'lunas' : $sale->payment_status;

        $sale->update([
            'down_payment' => $newDownPayment,
            'remaining_payment' => $newRemainingPayment,
            'payment_status' => $paymentStatus,
        ]);

        // Redirect instead of returning a view directly
        return redirect()->route('manager.sales.index')->with('success', 'Sale updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
