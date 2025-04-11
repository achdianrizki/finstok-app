<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\ReturnPurchase;
use Illuminate\Support\Facades\DB;

class ReturnPurchaseController extends Controller
{
    public function index()
    {
        $purchase_items = Purchase::with(['item'])->get();

        return view('manager.return.purchase.index', compact('purchase_items'));
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

    public function show(Purchase $purchase)
    {
        $purchase->load('supplier');
        $warehouses = Warehouse::all();
        $suppliers = Supplier::all();
        $categories = Category::all();
        $items = Item::all();

        return view('manager.return.purchase.create', compact('purchase', 'warehouses', 'suppliers', 'categories', 'items'));
    }

    public function returnPurchase(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $purchase = Purchase::findOrFail($id);
            $total_return = 0;
            $total_tax_return = 0;

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
                $ppn_return = ($purchase->tax_type == 'ppn') ? $return_value * 0.11 : 0;

                $total_return += $return_value;
                $total_price = $return_value + $ppn_return;
                $total_tax_return += $ppn_return;

                // dd($total_return, $total_tax_return, $qty, $ppn_return);

                if ($item) {
                    $item->decrement('stock', $qty);
                    $purchase->decrement('total_qty', $qty);
                    

                    $pivotData = $purchase->items()->wherePivot('item_id', $item->id)->first();
                    if ($pivotData) {
                        $newQty = $pivotData->pivot->qty - $qty;
                        // dd($newQty);
                        if ($newQty > 0) {
                            $purchase->items()->updateExistingPivot($item->id, [
                                'qty' => $newQty,
                            ]);
                        } else {
                            dd('Item sold out');
                        }
                    }

                    $pivotWarehouse = $item->item_warehouse()->wherePivot('item_id', $item->id)->first();
                    if ($pivotWarehouse) {
                        $newStockWarehouse = $pivotWarehouse->pivot->stock - $qty;

                        $item->item_warehouse()->updateExistingPivot($pivotWarehouse->pivot->warehouse_id, [
                            'stock' => max($newStockWarehouse, 0),
                            'original_stock' => max($newStockWarehouse, 0),
                            'physical' => max($newStockWarehouse, 0),
                        ]);
                    }

                    $return->items()->attach($item->id, [
                        'qty'            => $qty,
                        'price_per_item' => $price_per_item,
                    ]);
                }
            }

            $return->update(['total_return' => $total_return]);

            $purchase->decrement('total_price', $total_price);
            $purchase->decrement('sub_total', $total_return);
            $purchase->decrement('tax', $total_tax_return);

            toast('Return berhasil dilakukan dan total harga serta PPN berkurang', 'success');
            return redirect()->route('manager.return.purchase');
        });
    }
}
