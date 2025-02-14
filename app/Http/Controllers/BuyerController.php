<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBuyerRequest;
use App\Http\Requests\UpdateBuyerRequest;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('manager.buyer.index');
    }

    public function getBuyers(Request $request)
    {
        $query = Buyer::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $buyers = $query->paginate(5);

        return response()->json($buyers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manager.buyer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBuyerRequest $request)
    {
        $validatedData = $request->validated();

        Buyer::create($validatedData);

        toast('Pelanggan berhasil ditambahkan', 'success');
        return redirect()->route('manager.other.buyer.index')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Buyer $buyer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buyer $buyer)
    {
        return view('manager.buyer.edit', compact('buyer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBuyerRequest $request, Buyer $buyer)
    {
        $validatedData = $request->validated();
        
        $buyer->update($validatedData);

        toast('Pelanggan berhasil diupdate', 'success');
        return redirect()->route('manager.other.buyer.index')->with('success','Pelanggan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Buyer $buyer)
    {
        $buyer->delete();

        toast('Pelanggan berhasil dihapus', 'success');
        return redirect()->route('manager.other.buyer.index')->with('success', 'Pelanggan berhasil dihapus');
    }
}
