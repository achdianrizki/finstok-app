<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Item;
use App\Models\Sale;
use App\Models\Buyer;
use App\Models\Category;
use App\Models\Salesman;
use App\Models\Warehouse;

use App\Models\Distributor;
use Illuminate\Http\Request;
use App\Models\IncomingPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Http\Requests\StoreSaleRequest;
use function PHPUnit\Framework\isEmpty;
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
        // SELECT ITEM
        $items = Item::all();
        $buyers = Buyer::all();
        $salesmans = Salesman::all();
        $warehouses = Warehouse::all();

        return view('manager.finance.sales.create', compact('items', 'buyers', 'salesmans', 'warehouses'));
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


    public function getSales()
    {
        $sales = Sale::with(['item', 'distributor', 'buyer'])
            ->paginate(5);

        return response()->json($sales);
    }

    public function getSaleItems(Request $request)
    {
        $search = $request->query('search');
        $period = $request->query('period');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $salesQuery = Sale::with(['buyer']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $salesQuery->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', '%' . $search . '%')
                    ->orWhereHas('buyer', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%')
                            ->orWhere('contact', 'like', '%' . $search . '%');
                    })
                    ->orWhere('sale_date', 'like', '%' . $search . '%')->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        if ($period == 'day') {
            $salesQuery->whereDate('sale_date', Carbon::today());
        } elseif ($period == 'month') {
            $salesQuery->whereMonth('sale_date', Carbon::now()->month)
                ->whereYear('sale_date', Carbon::now()->year);
        } elseif ($period == 'custom' && $startDate && $endDate) {
            $salesQuery->whereBetween('sale_date', [$startDate, $endDate]);
        }

        $sales = $salesQuery->paginate(5);

        return response()->json($sales);
    }

    public function getSaleItemsBySalesman(Request $request)
    {
        $search = $request->query('search');
        $salesman_id = $request->query('salesman_id');

        $salesQuery = Sale::with('salesman', 'buyer');

        // Filter berdasarkan pencarian
        $salesQuery->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', "%$search%")
                    ->orWhere('sale_date', 'like', "%$search%")
                    ->orWhere('status', 'like', "%$search%")
                    ->orWhereHas('buyer', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%")
                            ->orWhere('contact', 'like', "%$search%");
                    })
                    ->orWhereHas('salesman', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    });
            });
        });

        // Filter berdasarkan supplier_id (jika diperlukan)
        $salesQuery->when($salesman_id, function ($query, $salesman_id) {
            $query->where('salesman_id', $salesman_id);
        });

        // Ambil data dengan pagination
        $sales = $salesQuery->paginate(5)->appends($request->query());

        return response()->json($sales);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $lastSale = Sale::latest()->first();

        $date = Carbon::parse($request->sale_date);
        $year = $date->year;
        $month = str_pad($date->month, 2, '0', STR_PAD_LEFT);

        $lastNumber = Sale::whereYear('sale_date', $year)
            ->whereMonth('sale_date', $date->month)
            ->latest('sale_number')
            ->value('sale_number');

        $newNumber = $lastNumber ? (int) substr($lastNumber, -7, 3) + 1 : 1;

        $sale_number = 'SEVENA/SALE/' . $month . '/' . str_pad($newNumber, 4, '0', STR_PAD_LEFT) . '/' . $year;

        $qty_sold = array_sum($request->qty_sold);

        $total_discount = str_replace(['Rp', '.', ','], ['', '', '.'], $request->discount1_value) +
            str_replace(['Rp', '.', ','], ['', '', '.'], $request->discount2_value) +
            str_replace(['Rp', '.', ','], ['', '', '.'], $request->discount3_value);

        $sale = Sale::create([
            'buyer_id' => $request->buyer_id,
            'salesman_id' => $request->salesman_id,
            'sale_number' => $sale_number,
            'total_price' => str_replace(['Rp', '.', ','], ['', '', '.'], $request->total_price),
            'sub_total' => str_replace(['Rp', '.', ','], ['', '', '.'], $request->sub_total),
            'discount1_value' => str_replace(['Rp', '.', ','], ['', '', '.'], $request->discount1_value),
            'discount2_value' => str_replace(['Rp', '.', ','], ['', '', '.'], $request->discount2_value),
            'discount3_value' => str_replace(['Rp', '.', ','], ['', '', '.'], $request->discount3_value),
            'total_discount' => $total_discount,
            'sale_date' => $request->sale_date,
            'payment_method' => $request->payment_method,
            'tax' => str_replace(['Rp', '.', ','], ['', '', '.'], $request->tax),
            'tax_type' => $request->tax_type,
            'status' => $request->status,
            'information' => $request->information,
            'qty_sold' => $qty_sold,
            'due_date_duration' => $request->due_date_duration,
            'due_date' => $request->due_date,

        ]);

        foreach ($request->items as $index => $item_id) {
            $item = Item::findOrFail($item_id);
            if ($item) {
                $qty = (int) ($request->qty_sold[$index] ?? 0);
                $ad = (int) ($request->ad[$index] ?? 0);

                if ($qty > 0) {
                    $item->decrement('stock', $qty);
                    $item->item_warehouse()->wherePivot('warehouse_id', $request->warehouse_id)->decrement('stock', $qty);
                }

                if ($ad > 0) {
                    $item->decrement('stock', $ad);
                    $item->item_warehouse()->wherePivot('warehouse_id', $request->warehouse_id)->decrement('stock', $ad);
                }

                $sale_price = str_replace(',', '.', str_replace('.', '', $request->sale_prices[$index]));

                $item->sales()->attach($sale->id, [
                    'qty_sold' => $request->qty_sold[$index],
                    'discount1' => $request->discount1[$index],
                    'discount2' => $request->discount2[$index],
                    'discount3' => $request->discount3[$index],
                    'sale_price' => $sale_price,
                    'warehouse_id' => $request->warehouse_id,
                    'ad' => $request->ad[$index] ?? 0,
                ]);
            }
        }

        $warehouse_id = $request->warehouse_id;

        foreach ($request->items as $data => $itemId) {
            $item = Item::find($itemId);
            if ($item) {
                $qty = (int) ($request->qty_sold[$index] ?? 0);
                $ad = (int) ($request->ad[$index] ?? 0);

                $sale_price = (float) str_replace(',', '.', str_replace('.', '', $request->sale_prices[$data]));

                $existing = $item->item_warehouse()
                    ->wherePivot('warehouse_id', $warehouse_id)
                    ->first();

                if ($existing) {
                    $item->item_warehouse()->updateExistingPivot($request->warehouse_id, [
                        // 'stock' => $existing->pivot->stock - $qty,
                        'price_per_item' => $sale_price,
                        'physical' => $existing->pivot->physical - $qty - $ad,
                        'profit' => $existing->pivot->profit + $qty,
                        'difference' => $existing->pivot->difference + ($qty * $sale_price),
                    ]);
                } else {
                    $item->item_warehouse()->attach($request->warehouse_id, [
                        // 'stock' => $qty,
                        'price_per_item' => $sale_price,
                        'physical' => $qty - $ad,
                        'profit' => $qty,
                        'difference' => $qty * $sale_price,
                    ]);
                }
            }
        }


        toast('Data penjualan berhasil disimpan', 'success');
        return redirect()->route('manager.sales.index')->with('success', 'Data penjualan berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */

    public function show() {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $sale->load('buyer', 'items');
        $warehouses = Warehouse::all();
        $buyers = Buyer::all();
        $categories = Category::all();
        $items = Item::all();
        $salesmans = Salesman::all();
        return view('manager.finance.sales.edit', compact('sale', 'warehouses', 'salesmans', 'buyers', 'categories', 'items'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        return DB::transaction(function () use ($request, $sale) {
            $qty_sold = array_sum($request->qty_sold);

            $date = Carbon::parse($request->sale_date);
            $year = $date->year;
            $month = str_pad($date->month, 2, '0', STR_PAD_LEFT);

            $lastNumber = Sale::whereYear('sale_date', $year)
                ->whereMonth('sale_date', $date->month)
                ->latest('sale_number')
                ->value('sale_number');

            $newNumber = $lastNumber ? (int) substr($lastNumber, -7, 3) + 1 : 1;

            // $sale_number = 'SEVENA/SALE/' . $month . '/' . str_pad($newNumber, 4, '0', STR_PAD_LEFT) . '/' . $year;

            $total_discount = str_replace(['Rp', '.', ','], ['', '', '.'], $request->discount1_value) +
                str_replace(['Rp', '.', ','], ['', '', '.'], $request->discount2_value) +
                str_replace(['Rp', '.', ','], ['', '', '.'], $request->discount3_value);

            $sale->update([
                'buyer_id' => $request->buyer_id,
                'salesman_id' => $request->salesman_id,
                // 'sale_number' => $sale_number,
                'total_price' => str_replace(['Rp', '.', ','], ['', '', '.'], $request->total_price),
                'sub_total' => str_replace(['Rp', '.', ','], ['', '', '.'], $request->sub_total),
                'discount1_value' => str_replace(['Rp', '.', ','], ['', '', '.'], $request->discount1_value),
                'discount2_value' => str_replace(['Rp', '.', ','], ['', '', '.'], $request->discount2_value),
                'discount3_value' => str_replace(['Rp', '.', ','], ['', '', '.'], $request->discount3_value),
                'total_discount' => $total_discount,
                'sale_date' => $request->sale_date,
                'payment_method' => $request->payment_method,
                'tax' => str_replace(['Rp', '.', ','], ['', '', '.'], $request->tax),
                'tax_type' => $request->tax_type,
                'status' => $request->status,
                'information' => $request->information,
                'qty_sold' => $qty_sold,
                'due_date_duration' => $request->due_date_duration,
                'due_date' => $request->due_date,
            ]);

            // dd($request->all());

            $warehouse_id = $request->warehouse_id;

            foreach ($request->items as $index => $item_id) {
                $item = Item::find($item_id);

                if ($item) {
                    $qty_sold = $request->qty_sold[$index];
                    $sale_price = (float) str_replace(',', '.', str_replace('.', '', $request->sale_prices[$index]));
                    $ad = $request->ad[$index] ?? 0;

                    $existingSale = $item->sales()
                        ->wherePivot('sale_id', $sale->id)
                        ->wherePivot('warehouse_id', $warehouse_id)
                        ->get();

                    $previousQty = $existingSale->sum(function ($sale) {
                        return $sale->pivot->qty_sold ?? 0;
                    });

                    $previousAd = $existingSale->sum(fn($sale) => $sale->pivot->ad ?? 0);


                    if ($existingSale->isNotEmpty()) {
                        $item->sales()->updateExistingPivot($sale->id, [
                            'qty_sold'            => $qty_sold,
                            'sale_price' => $sale_price,
                            'discount1'      => $request->discount1[$index] ?? 0,
                            'discount2'      => $request->discount2[$index] ?? 0,
                            'discount3'      => $request->discount3[$index] ?? 0,
                            'ad'             => $request->ad[$index] ?? 0,
                            'warehouse_id'   => $warehouse_id
                        ]);
                    } else {
                        $item->sales()->attach($sale->id, [
                            'qty_sold'            => $qty_sold,
                            'sale_price' => $sale_price,
                            'discount1'      => $request->discount1[$index] ?? 0,
                            'discount2'      => $request->discount2[$index] ?? 0,
                            'discount3'      => $request->discount3[$index] ?? 0,
                            'ad'             => $request->ad[$index] ?? 0,
                            'warehouse_id'   => $warehouse_id
                        ]);
                    }

                    $existingStock = $item->item_warehouse()
                        ->wherePivot('warehouse_id', $warehouse_id)
                        ->first();

                    $differenceQty = $qty_sold - $previousQty; // Hitung selisih stok
                    $differenceAd = $ad - $previousAd; // Hitung selisih ad

                    if ($existingStock) {
                        if ($differenceQty > 0) {
                            $item->item_warehouse()->decrement('stock', $differenceQty);
                            $item->item_warehouse()->decrement('physical', $differenceQty);
                        } elseif ($differenceQty < 0) {
                            $item->item_warehouse()->increment('stock', abs($differenceQty));
                            $item->item_warehouse()->increment('physical', abs($differenceQty));
                        }

                        if ($differenceAd > 0) {
                            $item->item_warehouse()->decrement('stock', $differenceAd);
                            $item->item_warehouse()->decrement('physical', $differenceAd);
                        } elseif ($differenceAd < 0) {
                            $item->item_warehouse()->increment('stock', abs($differenceAd));
                            $item->item_warehouse()->increment('physical', abs($differenceAd));
                        }
                    } else {
                        $item->item_warehouse()->attach($warehouse_id, [
                            'stock'         => $qty_sold,
                            'physical'         => $qty_sold - $ad,
                            'price_per_item' => $sale_price,
                        ]);
                    }

                    // Perbarui total stok item
                    if ($differenceQty > 0 || $differenceAd > 0) {
                        $item->decrement('stock', $differenceQty + $differenceAd);
                    } elseif ($differenceQty < 0 || $differenceAd < 0) {
                        $item->increment('stock', abs($differenceQty) + abs($differenceAd));
                    }
                }
            }

            toast('Data Penjualan berhasil diperbarui', 'success');
            return redirect()->route('manager.sales.index');
        });
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        // Hapus relasi dengan items sebelum menghapus sale
        $sale->items()->detach();

        // Hapus sale
        $sale->delete();

        toast('Data penjualan berhasil dihapus', 'success');
        return redirect()->route('manager.sales.index')->with('success', 'Data penjualan berhasil dihapus');
    }

    public function deleteItem(Sale $sale, Item $item)
    {
        $pivot = $sale->items()->where('item_id', $item->id)->first()->pivot ?? null;
        $qtyToRemove = $pivot ? $pivot->qty_sold : 0;

        $warehouse_id = $pivot ? $pivot->warehouse_id : null;

        $sale->items()->detach($item->id);

        if ($warehouse_id) {
            $existing = $item->item_warehouse()->wherePivot('warehouse_id', $warehouse_id)->first();
            if ($existing) {
                $item->item_warehouse()->updateExistingPivot($warehouse_id, [
                    'stock' => $existing->pivot->stock + $qtyToRemove,
                ]);
            }
        }

        $sale->decrement('qty_sold', $qtyToRemove);

        if ($qtyToRemove > 0) {
            $item->increment('stock', $qtyToRemove);
        }

        return response()->json(['success' => true]);
    }
}
