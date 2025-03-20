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
