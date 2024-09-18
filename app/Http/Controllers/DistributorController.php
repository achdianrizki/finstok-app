<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDistributorRequest;
use App\Http\Requests\UpdateDistributorRequest;
use App\Models\Distributor;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('manager.distributor.index');
    }

    public function getDistributors(Request $request)
    {
        $query = Distributor::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $distributors = $query->paginate(5);

        return response()->json($distributors);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manager.distributor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDistributorRequest $request)
    {
        $validatedData = $request->validated();

        Distributor::create($validatedData);

        return redirect()->route('manager.distributors.index')->with('success', 'Distributor added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Distributor $distributor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Distributor $distributor)
    {
        return view('manager.distributor.edit', compact('distributor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDistributorRequest $request, Distributor $distributor)
    {
        $validatedData = $request->validated();
        
        $distributor->update($validatedData);

        return redirect()->route('manager.distributors.index')->with('success','Distributor updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Distributor $distributor)
    {
        $distributor->delete();

        return redirect()->route('manager.distributors.index')->with('success', 'Distributor deleted successfully');
    }
}
