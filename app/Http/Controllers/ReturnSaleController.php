<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\ReturnSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnSaleController extends Controller
{
    public function index()
    {
        return view('manager.return.sale.index');
    }

    public function getSaleItem(Request $request)
    {
        $query = Sale::with(['buyer']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', '%' . $search . '%')
                    ->orWhereHas('buyer', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('sale_date', 'like', '%' . $search . '%')->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        $products = $query->paginate(5);

        return response()->json($products);
    }

    public function show(Sale $sale)
    {
        $items = Item::all();
        $buyer = $sale->buyer;
        $salesman = $sale->salesman;


        // return view('manager.finance.sales', compact('sale'));
        return view('manager.return.sale.create', compact('sale', 'items', 'buyer', 'salesman'));
    }

    public function returnSale(Request $request, $id)
    {
        return DB::transaction(function () use ($request, $id) {
            $sale = Sale::findOrFail($id);
            $total_return = 0;
            $total_tax_return = 0;

            // dd($request->all());
            $ppn_per_item = $sale->tax / $sale->qty_sold;

            $return = ReturnSale::create([
                'sale_id'      => $sale->id,    
                'buyer_id'     => $sale->buyer_id,
                'return_date'  => now(),
                'reason'       => $request->reason,
                'total_return' => 0,
            ]);

            foreach ($request->items as $index => $item_id) {
                $item = Item::find($item_id);
                $qty = $request->qty[$index];
                $price_per_item = $request->price_per_item[$index];

                $return_value = $qty * $price_per_item;
                if ($sale->tax_type == 'ppn') {
                    $ppn_return = $return_value * 0.11;
                } else {
                    $ppn_return = 0;
                }
                $total_return += $return_value;
                $total_tax_return += $ppn_return;

                // dd("Qty: $qty", "Price per item: $price_per_item", "Return Value: $return_value", "PPN Return: $ppn_return");

                if ($item) {
                    $item->decrement('stock', $qty);

                    $pivotData = $sale->items()->wherePivot('item_id', $item->id)->first();
                    $pivotWarehouse = $item->item_warehouse()->wherePivot('item_id', $item->id)->first();

                    if ($pivotData) {
                        $newQty = $pivotData->pivot->qty + $qty;

                        if ($newQty > 0) {
                            $sale->items()->updateExistingPivot($item->id, [
                                'qty_sold' => $newQty,
                            ]);
                        } else {
                            $sale->items()->detach($item->id);
                        }
                    } else {
                        dd("Item tidak ditemukan di pivot item_purchase", $item->id, $sale->id);
                    }

                    if ($pivotWarehouse) {
                        $newStockWarehouse = $pivotWarehouse->pivot->stock + $qty;

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
                        dd("Item tidak ditemukan di pivot item_warehouse", $item->id, $sale->id);
                    }

                    $return->items()->attach($item->id, [
                        'qty'            => $qty,
                        'price_per_item' => $price_per_item,
                    ]);
                }
            }



            $subtotal = $sale->subtotal - $return_value;
            // dd($total_return, $subtotal);
            $return->update(['total_return' => $total_return]);

            $sale->decrement('total_price', $total_return);
            $sale->decrement('sub_total', $subtotal);
            $sale->decrement('tax', $total_tax_return);

            toast('Return berhasil dilakukan dan total harga serta PPN berkurang', 'success');
            return redirect()->route('manager.purchase.index');
        });
    }
}
