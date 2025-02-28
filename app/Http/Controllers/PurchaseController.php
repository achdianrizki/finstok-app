<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
        $query = Purchase::with(['supplier'])->latest();

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
        return DB::transaction(function () use ($request) {
            $total_qty = array_sum($request->qty);

            $date = Carbon::parse($request->purchase_date);
            $year = $date->year;
            $month = str_pad($date->month, 2, '0', STR_PAD_LEFT);

            $lastNumber = Purchase::whereYear('purchase_date', $year)
                ->whereMonth('purchase_date', $date->month)
                ->latest('purchase_number')
                ->value('purchase_number');

            $newNumber = $lastNumber ? (int) substr($lastNumber, -7, 3) + 1 : 1;

            $purchase_number = 'SDI/BUY/' . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT) . '/' . $year;

            $purchase = Purchase::create([
                'purchase_number'  => $purchase_number,
                'purchase_date'    => $request->purchase_date,
                'supplier_id'      => $request->supplier_id,
                'tax'              => $request->tax,
                'tax_type'         => $request->tax_type,
                'information'      => $request->information,
                'sub_total'        => $request->sub_total,
                'total_discount1'  => $request->total_discount1 ?? 0,
                'total_discount2'  => $request->total_discount2 ?? 0,
                'total_discount3'  => $request->total_discount3 ?? 0,
                'total_price'      => $request->total_price,
                'total_qty'        => $total_qty,
            ]);

            foreach ($request->items as $index => $item_id) {
                $item = Item::find($item_id);
                if ($item) {
                    $item->increment('stock', $request->qty[$index]);

                    $item->purchases()->attach($purchase->id, [
                        'qty'            => $request->qty[$index],
                        'price_per_item' => $request->price_per_item[$index],
                        'discount1'      => $request->discount1[$index] ?? 0,
                        'discount2'      => $request->discount2[$index] ?? 0,
                        'discount3'      => $request->discount3[$index] ?? 0,
                        'ad'             => $request->ad[$index] ?? 0,
                    ]);
                }
            }

            toast('Data berhasil disimpan', 'success');
            return redirect()->route('manager.purchase.index');
        });
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
