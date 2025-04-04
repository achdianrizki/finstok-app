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
                $total_tax_return += $ppn_return;

                if ($item) {
                    // Tambahkan stok kembali ke item
                    $item->increment('stock', $qty);

                    // Update pivot item_purchase (penjualan)
                    $pivotData = $sale->items()->wherePivot('item_id', $item->id)->first();
                    if ($pivotData) {
                        $newQty = $pivotData->pivot->qty - $qty; // Mengurangi qty yang sudah terjual
                        if ($newQty > 0) {
                            $sale->items()->updateExistingPivot($item->id, [
                                'qty' => $newQty,
                            ]);
                        } else {
                            // Jika setelah return qty jadi nol atau negatif, hapus relasi dari pivot
                            $sale->items()->detach($item->id);
                        }
                    }

                    // Update stok di gudang
                    $pivotWarehouse = $item->item_warehouse()->wherePivot('item_id', $item->id)->first();
                    if ($pivotWarehouse) {
                        $newStockWarehouse = $pivotWarehouse->pivot->stock + $qty;
                        $item->item_warehouse()->updateExistingPivot($pivotWarehouse->pivot->warehouse_id, [
                            'stock' => max($newStockWarehouse, 0),
                        ]);
                    }

                    // Simpan ke return_items
                    $return->items()->attach($item->id, [
                        'qty'            => $qty,
                        'price_per_item' => $price_per_item,
                    ]);
                }
            }

            // Update total return ke return sale
            $return->update(['total_return' => $total_return]);

            // Kurangi total price, subtotal, dan tax pada penjualan
            $sale->decrement('total_price', $total_return);
            $sale->decrement('sub_total', $total_return);
            $sale->decrement('tax', $total_tax_return);

            toast('Return berhasil dilakukan dan total harga serta PPN berkurang', 'success');
            return redirect()->route('manager.return.sale');
        });
    }

}
