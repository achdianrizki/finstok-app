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
        // $query = Purchase::with(['supplier'])->latest();

        // if ($request->filled('search')) {
        //     $search = $request->search;

        //     $query->where(function ($q) use ($search) {
        //         $q->where('purchase_number', 'like', '%' . $search . '%')
        //             ->orWhereHas('supplier', function ($query) use ($search) {
        //                 $query->where('name', 'like', '%' . $search . '%');
        //             })
        //             ->orWhere('purchase_date', 'like', '%' . $search . '%');
        //     });
        // }

        // $products = $query->paginate(5);

        // return response()->json($products);

        $search = $request->query('search');
        $period = $request->query('period');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        $purchaseQuery = Purchase::with(['supplier']);

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $purchaseQuery->where(function ($q) use ($search) {
                $q->where('purchase_number', 'like', '%' . $search . '%')
                    ->orWhereHas('supplier', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('purchase_date', 'like', '%' . $search . '%');
            });
        }

        if ($period == 'day') {
            $purchaseQuery->whereDate('purchase_date', Carbon::today());
        } elseif ($period == 'month') {
            $purchaseQuery->whereMonth('purchase_date', Carbon::now()->month)
                ->whereYear('purchase_date', Carbon::now()->year);
        } elseif ($period == 'custom' && $startDate && $endDate) {
            $purchaseQuery->whereBetween('purchase_date', [$startDate, $endDate]);
        }

        $purchases = $purchaseQuery->paginate(5);

        return response()->json($purchases);
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

            $purchase_number = 'SEVENA/BUY/' . $month . '/' . str_pad($newNumber, 3, '0', STR_PAD_LEFT) . '/' . $year;

            $purchase = Purchase::create([
                'purchase_number'   => $purchase_number,
                'purchase_date'     => $request->purchase_date,
                'supplier_id'       => $request->supplier_id,
                'tax'               => (float) str_replace(',', '.', str_replace('.', '', $request->tax)),
                'tax_type'          => $request->tax_type,
                'information'       => $request->information ?? '-',
                'warehouse_id'      => $request->warehouse_id,
                'sub_total'         => (float) str_replace(',', '.', str_replace('.', '', $request->sub_total)),
                'total_discount1'   => (float) str_replace(',', '.', str_replace('.', '', $request->total_discount1 ?? 0)),
                'total_discount2'   => (float) str_replace(',', '.', str_replace('.', '', $request->total_discount2 ?? 0)),
                'total_discount3'   => (float) str_replace(',', '.', str_replace('.', '', $request->total_discount3 ?? 0)),
                'total_price'       => (float) str_replace(',', '.', str_replace('.', '', $request->total_price)),
                'total_qty'         => $total_qty,
                'due_date_duration' => $request->due_date_duration,
                'due_date'          => $request->due_date,
            ]);

            $warehouse_id = $request->warehouse_id;

            foreach ($request->items as $index => $item_id) {
                $item = Item::find($item_id);

                if ($item) {
                    $qty = (int) ($request->qty[$index] ?? 0);
                    $ad = (int) ($request->ad[$index] ?? 0);

                    $item->purchases()->attach($purchase->id, [
                        'qty'            => $qty,
                        'price_per_item' => (float) str_replace(',', '.', str_replace('.', '', $request->price_per_item[$index])),
                        'discount1'      => $request->discount1[$index] ?? 0,
                        'discount2'      => $request->discount2[$index] ?? 0,
                        'discount3'      => $request->discount3[$index] ?? 0,
                        'ad'             => $ad,
                        'warehouse_id'   => $warehouse_id
                    ]);
                }
            }

            foreach ($request->items as $index => $itemId) {
                $item = Item::find($itemId);

                if ($item) {
                    $qty = (int) ($request->qty[$index] ?? 0);
                    $ad = (int) ($request->ad[$index] ?? 0);
                    $price_per_item = (float) str_replace(',', '.', str_replace('.', '', $request->price_per_item[$index]));

                    $existing = $item->item_warehouse()
                        ->wherePivot('warehouse_id', $warehouse_id)
                        ->first();

                    if ($existing) {
                        $newStock = $existing->pivot->stock + $qty + $ad + $existing->pivot->profit;
                        $item->item_warehouse()->updateExistingPivot($warehouse_id, [
                            'stock'          => $newStock,
                            'original_stock' => $newStock,
                            'physical'       => $existing->pivot->physical + $qty + $ad + $existing->pivot->profit,
                            'price_per_item' => $price_per_item,
                        ]);

                        $adjustment = $qty + $existing->pivot->profit;
                        if ($adjustment > 0) {
                            $item->increment('stock', $adjustment);
                        }
                    } else {
                        $initialStock = $qty + $ad;
                        $item->item_warehouse()->attach($warehouse_id, [
                            'stock'          => $initialStock,
                            'original_stock' => $initialStock,
                            'physical'       => $initialStock,
                            'price_per_item' => $price_per_item,
                        ]);

                        // Tambah ke total stock item utama
                        $item->increment('stock', $initialStock);
                    }
                }
            }

            toast('Data berhasil disimpan', 'success');
            return redirect()->route('manager.purchase.index');
        });
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        return DB::transaction(function () use ($request, $purchase) {
            $total_qty = array_sum($request->qty);

            $purchase->update([
                'purchase_date'     => $request->purchase_date,
                'supplier_id'       => $request->supplier_id,
                'tax'               => (float) str_replace(',', '.', str_replace('.', '', $request->tax)),
                'tax_type'          => $request->tax_type,
                'information'       => $request->information ?? '-',
                'warehouse_id'      => $request->warehouse_id,
                'sub_total'         => (float) str_replace(',', '.', str_replace('.', '', $request->sub_total)),
                'total_discount1'   => (float) str_replace(',', '.', str_replace('.', '', $request->total_discount1 ?? 0)),
                'total_discount2'   => (float) str_replace(',', '.', str_replace('.', '', $request->total_discount2 ?? 0)),
                'total_discount3'   => (float) str_replace(',', '.', str_replace('.', '', $request->total_discount3 ?? 0)),
                'total_price'       => (float) str_replace(',', '.', str_replace('.', '', $request->total_price)),
                'total_qty'         => $total_qty,
                'due_date_duration' => $request->due_date_duration,
                'due_date'          => $request->due_date,
                'status'            => $request->status,
            ]);

            $warehouse_id = $request->warehouse_id;

            foreach ($request->items as $index => $item_id) {
                $item = Item::find($item_id);

                if ($item) {
                    $qty = (int) ($request->qty[$index] ?? 0);
                    $ad = (int) ($request->ad[$index] ?? 0);
                    $price_per_item = (float) str_replace(',', '.', str_replace('.', '', $request->price_per_item[$index]));

                    $existingPurchase = $item->purchases()
                        ->wherePivot('purchase_id', $purchase->id)
                        ->wherePivot('warehouse_id', $warehouse_id)
                        ->first();

                    $previousQty = $existingPurchase?->pivot->qty ?? 0;
                    $previousAd  = $existingPurchase?->pivot->ad ?? 0;

                    // Update pivot purchase-item
                    if ($existingPurchase) {
                        $item->purchases()->updateExistingPivot($purchase->id, [
                            'qty'            => $qty,
                            'price_per_item' => $price_per_item,
                            'discount1'      => $request->discount1[$index] ?? 0,
                            'discount2'      => $request->discount2[$index] ?? 0,
                            'discount3'      => $request->discount3[$index] ?? 0,
                            'ad'             => $ad,
                            'warehouse_id'   => $warehouse_id
                        ]);
                    } else {
                        $item->purchases()->attach($purchase->id, [
                            'qty'            => $qty,
                            'price_per_item' => $price_per_item,
                            'discount1'      => $request->discount1[$index] ?? 0,
                            'discount2'      => $request->discount2[$index] ?? 0,
                            'discount3'      => $request->discount3[$index] ?? 0,
                            'ad'             => $ad,
                            'warehouse_id'   => $warehouse_id
                        ]);
                    }

                    // Update ke item_warehouse
                    $existingStock = $item->item_warehouse()
                        ->wherePivot('warehouse_id', $warehouse_id)
                        ->first();

                    $differenceQty = $qty - $previousQty;
                    $differenceAd  = $ad - $previousAd;

                    if ($existingStock) {
                        $newStock = $existingStock->pivot->stock + $differenceQty + $differenceAd;
                        $newPhysical = $existingStock->pivot->physical + $differenceQty + $differenceAd;

                        $item->item_warehouse()->updateExistingPivot($warehouse_id, [
                            'stock'          => $newStock,
                            'original_stock' => $newStock,
                            'physical'       => $newPhysical,
                            'price_per_item' => $price_per_item,
                        ]);

                        $totalDiff = $differenceQty + $differenceAd;
                        if ($totalDiff > 0) {
                            $item->increment('stock', $totalDiff);
                        } elseif ($totalDiff < 0) {
                            $item->decrement('stock', abs($totalDiff));
                        }
                    } else {
                        $initialStock = $qty + $ad;
                        $item->item_warehouse()->attach($warehouse_id, [
                            'stock'          => $initialStock,
                            'original_stock' => $initialStock,
                            'physical'       => $initialStock,
                            'price_per_item' => $price_per_item,
                        ]);

                        $item->increment('stock', $initialStock);
                    }
                }
            }

            toast('Data berhasil diperbarui', 'success');
            return redirect()->route('manager.purchase.index');
        });
    }


    public function deleteItem(Purchase $purchase, Item $item)
    {
        $pivot = $purchase->items()->where('item_id', $item->id)->first()->pivot ?? null;
        $qtyToRemove = $pivot ? $pivot->qty : 0;

        $warehouse_id = $pivot ? $pivot->warehouse_id : null;

        $purchase->items()->detach($item->id);

        if ($warehouse_id) {
            $item->item_warehouse()->wherePivot('warehouse_id', $warehouse_id)->detach();
        }

        $purchase->decrement('total_qty', $qtyToRemove);

        if ($qtyToRemove > 0) {
            $item->decrement('stock', $qtyToRemove);
        }

        return response()->json(['success' => true]);
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
        $purchase->load('supplier', 'items');
        $warehouses = Warehouse::all();
        $suppliers = Supplier::all();
        $categories = Category::all();
        $items = Item::all();

        return view('manager.purchase.edit', compact('purchase', 'warehouses', 'suppliers', 'categories', 'items'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
