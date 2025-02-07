<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreModalRequest;
use App\Http\Requests\UpdateModalRequest;
use App\Models\Modal;
use Illuminate\Http\Request;

class ModalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modals = Modal::orderBy("id", "desc")->paginate(5);
        // $modals = 'Rp. ' . number_format($modals, 2);

        return view('manager.finance.modal.index', compact('modals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreModalRequest $request)
    {
        $validatedData = $request->validated();
        $cleanAmount = str_replace('.', '', $validatedData);

        $validatedData['amount'] = $cleanAmount['amount'];
        $validatedData['initial_amount'] = $cleanAmount['amount'];

        Modal::create($validatedData);

        return redirect()->route('manager.finance.modal.primaryModal')->with('success', 'Modal berhasil diajukan! Menunggu persetujuan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Modal $modal)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modal $modal)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateModalRequest $request, Modal $modal)
    {
        $validatedData = $request->validated();
        $modal->update($validatedData);

        return redirect()->route('manager.modal.index')->with('success', 'Modal updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Modal $modal)
    {
        $modal->delete();

        return redirect()->route('manager.modal.index')->with('success', 'Modal added successfully');
    }

    public function updateStatus(Modal $modal)
    {
        $modal->is_confirm = true;
        $modal->save();

        return redirect()->route('manager.modal.index')->with('success', 'Status modal berhasil diubah menjadi Approved.');
    }

    public function primaryModal()
    {
        $primaryModal = Modal::where('is_confirm', true)->sum('amount');
        $primaryModal = 'Rp. ' . number_format($primaryModal, 2);

        return view('manager.finance.primaryModal.index', compact('primaryModal'));
    }
}
