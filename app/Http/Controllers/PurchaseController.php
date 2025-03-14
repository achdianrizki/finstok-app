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
use App\Models\ReturnPurchase;
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
        $warehouses = Warehouse::all();
        $items = Item::all();
        return view('manager.purchase.create', compact('suppliers', 'items', 'warehouses'));
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
                'tax'              => (float) str_replace(',', '.', str_replace('.', '', $request->tax)),
                'tax_type'         => $request->tax_type,
                'information'      => $request->information ?? '-',
                'warehouse_id'     => $request->warehouse_id,
                'sub_total'        => (float) str_replace(',', '.', str_replace('.', '', $request->sub_total)),
                'total_discount1'  => (float) str_replace(',', '.', str_replace('.', '', $request->total_discount1 ?? 0)),
                'total_discount2'  => (float) str_replace(',', '.', str_replace('.', '', $request->total_discount2 ?? 0)),
                'total_discount3'  => (float) str_replace(',', '.', str_replace('.', '', $request->total_discount3 ?? 0)),
                'total_price'      => (float) str_replace(',', '.', str_replace('.', '', $request->total_price)),
                'total_qty'        => $total_qty,
            ]);

            // dd($request->all());

            $warehouse_id = $request->warehouse_id;

            foreach ($request->items as $index => $item_id) {
                $item = Item::find($item_id);

                if ($item) {
                    $item->increment('stock', $request->qty[$index]);

                    $item->purchases()->attach($purchase->id, [
                        'qty'            => $request->qty[$index],
                        'price_per_item' => (float) str_replace(',', '.', str_replace('.', '', $request->price_per_item[$index])),
                        'discount1'      => $request->discount1[$index] ?? 0,
                        'discount2'      => $request->discount2[$index] ?? 0,
                        'discount3'      => $request->discount3[$index] ?? 0,
                        'ad'             => $request->ad[$index] ?? 0,
                        'warehouse_id'   => $warehouse_id
                    ]);
                }
            }

            foreach ($request->items as $data => $itemId) {
                $item = Item::find($itemId);
                if ($item) {
                    $qty = $request->qty[$data];
                    $price_per_item = (float) str_replace(',', '.', str_replace('.', '', $request->price_per_item[$data]));

                    $existing = $item->item_warehouse()
                        ->wherePivot('warehouse_id', $warehouse_id)
                        ->first();

                    if ($existing) {
                        $item->item_warehouse()->updateExistingPivot($warehouse_id, [
                            'stock' => $existing->pivot->stock + $qty,
                            'price_per_item' => $price_per_item
                        ]);
                    } else {
                        $item->item_warehouse()->attach($warehouse_id, [
                            'stock'         => $qty,
                            'price_per_item' => $price_per_item,
                        ]);
                    }
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

    

    public function returnPurchase(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $purchase = Purchase::findOrFail($id);
            $total_return = 0;
            $total_tax_return = 0;
            $ppn_per_item = $purchase->tax / $purchase->total_qty;

            $return = ReturnPurchase::create([
                'purchase_id'  => $purchase->id,
                'supplier_id'  => $purchase->supplier_id,
                'return_date'  => now(),
                'reason'       => $request->reason,
                'total_return' => 0,
            ]);

            foreach ($request->items as $index => $item_id) {
                $item = Item::find($item_id);
                $qty = $request->qty[$index];
                $price_per_item = $request->price_per_item[$index];

                $return_value = $qty * $price_per_item;
                if ($purchase->tax_type == 'ppn') {
                    $ppn_return = $return_value * 0.11;
                } else {
                    $ppn_return = 0;
                }
                $total_return += $return_value;
                $total_tax_return += $ppn_return;

                // dd("Qty: $qty", "Price per item: $price_per_item", "Return Value: $return_value", "PPN Return: $ppn_return");

                if ($item) {
                    $item->decrement('stock', $qty);

                    $pivotData = $purchase->items()->wherePivot('item_id', $item->id)->first();
                    $pivotWarehouse = $item->item_warehouse()->wherePivot('item_id', $item->id)->first();

                    if ($pivotData) {
                        $newQty = $pivotData->pivot->qty - $qty;

                        if ($newQty > 0) {
                            $purchase->items()->updateExistingPivot($item->id, [
                                'qty' => $newQty,
                            ]);
                        } else {
                            $purchase->items()->detach($item->id);
                        }
                    } else {
                        dd("Item tidak ditemukan di pivot item_purchase", $item->id, $purchase->id);
                    }

                    if ($pivotWarehouse) {
                        $newStockWarehouse = $pivotWarehouse->pivot->stock - $qty;

                        if ($newStockWarehouse > 0) {
                            $item->item_warehouse()->updateExistingPivot($pivotWarehouse->pivot->warehouse_id, [
                                'stock' => $newStockWarehouse,
                            ]);
                        } else {
                            $item->item_warehouse()->updateExistingPivot($pivotWarehouse->pivot->warehouse_id, [
                                'stock' => 0,
                            ]);
                        }
                    } else {
                        dd("Item tidak ditemukan di pivot item_warehouse", $item->id, $purchase->id);
                    }

                    $return->items()->attach($item->id, [
                        'qty'            => $qty,
                        'price_per_item' => $price_per_item,
                    ]);
                }
            }



            $subtotal = $purchase->subtotal - $return_value;
            // dd($total_return, $subtotal);
            $return->update(['total_return' => $total_return]);

            $purchase->decrement('total_price', $total_return);
            $purchase->decrement('sub_total', $subtotal);
            $purchase->decrement('tax', $total_tax_return);

            toast('Return berhasil dilakukan dan total harga serta PPN berkurang', 'success');
            return redirect()->route('manager.purchase.index');
        });
    }
}
