<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\OutgoingPayment;

class OutgoingPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('manager.outgoingpayment.index');
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
    public function create_payment(Purchase $purchase)
    {
        $suppliers = $purchase->supplier;
        return view('manager.outgoingpayment.create', compact('suppliers', 'purchase'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $purchase = Purchase::find($request->purchase_id);

        if (!$purchase) {
            return back()->withErrors(['error' => 'Purchase not found']);
        }

        $receipt_number = date('Y') . '/SDIPAY/' . rand(1000, 9999);

        $total_unpaid = $purchase->total_price - $request->amount_paid;

        OutgoingPayment::create([
            'supplier_id' => $request->supplier_id,
            'purchase_id' => $request->purchase_id,
            'receipt_number' => $receipt_number,
            'payment_date' => $request->payment_date,
            'note' => $request->note,
            'total_price' => $purchase->total_price,
            'payment_method' => $request->payment_method,
            'total_unpaid' => $total_unpaid,
            'amount_paid' => $request->amount_paid,
        ]);

        if ($total_unpaid <= 0) {
            $purchase->update(['status' => 'lunas']);
        } else {
            $purchase->update(['status' => 'belum_lunas']);
        }

        return redirect()->back()->with('success', 'Pembayaran berhasil disimpan');
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $purchase = Purchase::with('payments', 'supplier')->findOrFail($id);

        return view('manager.outgoingpayment.payment', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $suppliers = $purchase->supplier;
        dd($purchase, $suppliers);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
