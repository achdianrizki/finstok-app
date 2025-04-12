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

    public function getMutationData(Request $request)
    {
        $search = $request->input('search');

        $mutations = DB::table('mutations')
            ->join('warehouses as source', 'mutations.from_warehouse_id', '=', 'source.id')
            ->join('warehouses as destination', 'mutations.to_warehouse_id', '=', 'destination.id')
            ->select(
            'mutations.id',
            'mutations.mutated_at as mutation_date',
            'source.name as source_warehouse',
            'destination.name as destination_warehouse',
            DB::raw('SUM(mutations.qty) as total_items')
            )
            ->whereNull('mutations.deleted_at') // Ensure only non-deleted records are fetched
            ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('source.name', 'like', "%$search%")
                ->orWhere('destination.name', 'like', "%$search%")
                ->orWhere('mutations.mutated_at', 'like', "%$search%");
            });
            })
            ->groupBy('mutations.id', 'mutations.mutated_at', 'source.name', 'destination.name')
            ->paginate(10); // Pagination active

        return response()->json($mutations);
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

    public function destroy(Mutation $mutation)
    {
        $mutation->delete();
        
        toast('Delete Success!', 'success');
        return redirect()->back();

    }

    public function restore($id)
    {
        $mutation = Mutation::withTrashed()->findOrFail($id);
        $mutation->restore();

        toast('Restore Success!', 'success');
        return redirect()->route('manager.report.mutation');
    }

    public function deletedView()
    {
        $mutations = Mutation::onlyTrashed()->get();
        return view('manager.mutation.deleted', compact('mutations'));
    }
}
