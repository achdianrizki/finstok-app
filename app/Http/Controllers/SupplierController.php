<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('manager.supplier.index');
    }

    public function getSupplier(Request $request)
    {
        $query = DB::table('suppliers')->select('id', 'supplier_code', 'name', 'phone', 'address', 'city', 'province', 'status')->whereNull('deleted_at');;

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('supplier_code', 'like', '%' . $search . '%')
                    ->orWhere('name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhere('address', 'like', '%' . $search . '%')
                    ->orWhere('city', 'like', '%' . $search . '%')
                    ->orWhere('province', 'like', '%' . $search . '%');
            });
        }

        $users = $query->paginate(5);

        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manager.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        Supplier::create($request->validated());

        return redirect()->route('manager.supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {

        return view('manager.supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());

        return redirect()->route('manager.supplier.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Supplier $supplier)
    {
        $supplier->delete();
        // return redirect()->route('manager.supplier.index')->with('success', "Supplier {$supplier->name} telah dihapus secara permanen.");

    }

    public function deletedData()
    {
        $deletedSuppliers = DB::table('suppliers')
            ->select('id', 'supplier_code', 'name', 'npwp', 'phone', 'fax_nomor', 'address', 'city', 'province', 'status', 'deleted_at')
            ->whereNotNull('deleted_at')
            ->paginate(5);

        return view('manager.softdeletes.supplier.index', compact('deletedSuppliers'));
    }
}
