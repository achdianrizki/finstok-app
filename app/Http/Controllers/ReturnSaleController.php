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
        $query = Sale::with(['buyer'])->latest();

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
                $ppn_return = ($sale->tax_type == 'ppn') ? $return_value * 0.11 : 0;

                $total_return += $return_value;
                $total_price = $return_value + $ppn_return;
                $total_tax_return += $ppn_return;

                // dd($total_return, $total_tax_return, $qty * $price_per_item, $ppn_return);

                if ($item) {
                    $item->increment('stock', $qty);

                    $pivotData = $sale->items()->wherePivot('item_id', $item->id)->first();
                    if ($pivotData) {
                        $newQty = $pivotData->pivot->qty_sold - $qty;
                        // dd($newQty);
                        if ($newQty > 0) {
                            $sale->items()->updateExistingPivot($item->id, [
                                'qty_sold' => $newQty,
                            ]);
                        } else {
                            dd('Item sold out');
                        }
                    }

                    $pivotWarehouse = $item->item_warehouse()->wherePivot('item_id', $item->id)->first();
                    if ($pivotWarehouse) {
                        $newStockWarehouse = $pivotWarehouse->pivot->stock + $qty;
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

            $sale->decrement('total_price', $total_price);
            $sale->decrement('sub_total', $total_return);
            $sale->decrement('tax', $total_tax_return);

            toast('Return berhasil dilakukan dan total harga serta PPN berkurang', 'success');
            return redirect()->route('manager.return.sale');
        });
    }

}
