<?php

namespace App\Http\Controllers;

use App\Models\Mutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MutationController extends Controller
{
    public function getWarehouse(Request $request)
    {
        $warehouses = \App\Models\Warehouse::all();
        return response()->json($warehouses);
    }


    public function store(Request $request)
    {
        $mutation = null;

        DB::beginTransaction();

        try {
            $mutation = Mutation::create([
                'item_id'           => $request->item_id,
                'from_warehouse_id' => $request->from_warehouse_id,
                'to_warehouse_id'   => $request->to_warehouse_id,
                'qty'               => $request->qty,
                'note'              => $request->note,
            ]);

            DB::table('item_warehouse')
                ->where('item_id', $request->item_id)
                ->where('warehouse_id', $request->from_warehouse_id)
                ->decrement('stock', $request->qty);

            DB::table('item_warehouse')
                ->where('item_id', $request->item_id)
                ->where('warehouse_id', $request->from_warehouse_id)
                ->decrement('original_stock', $request->qty);

            DB::table('item_warehouse')
                ->where('item_id', $request->item_id)
                ->where('warehouse_id', $request->from_warehouse_id)
                ->decrement('physical', $request->qty);

            $remainingStock = DB::table('item_warehouse')
                ->where('item_id', $request->item_id)
                ->where('warehouse_id', $request->from_warehouse_id)
                ->value('stock');

            if ($remainingStock <= 0) {
                DB::table('item_warehouse')
                    ->where('item_id', $request->item_id)
                    ->where('warehouse_id', $request->from_warehouse_id)
                    ->delete();
            }

            $existing = DB::table('item_warehouse')
                ->where('item_id', $request->item_id)
                ->where('warehouse_id', $request->to_warehouse_id)
                ->first();

            if ($existing) {
                DB::table('item_warehouse')
                    ->where('item_id', $request->item_id)
                    ->where('warehouse_id', $request->to_warehouse_id)
                    ->increment('stock', $request->qty);

                DB::table('item_warehouse')
                    ->where('item_id', $request->item_id)
                    ->where('warehouse_id', $request->to_warehouse_id)
                    ->increment('original_stock', $request->qty);

                DB::table('item_warehouse')
                    ->where('item_id', $request->item_id)
                    ->where('warehouse_id', $request->to_warehouse_id)
                    ->increment('physical', $request->qty);
            } else {
                DB::table('item_warehouse')->insert([
                    'item_id'         => $request->item_id,
                    'warehouse_id'    => $request->to_warehouse_id,
                    'stock'           => $request->qty,
                    'price_per_item'  => $request->price_per_item,
                    'original_stock'  => $request->qty,
                    'physical'        => $request->qty,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Mutasi berhasil disimpan',
                'data' => $mutation
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menyimpan mutasi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
