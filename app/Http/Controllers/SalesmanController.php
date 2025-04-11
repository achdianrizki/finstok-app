<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalesmanRequest;
use App\Http\Requests\UpdateSalesmanRequest;
use App\Models\Salesman;
use Illuminate\Http\Request;

class SalesmanController extends Controller
{
  public function index()
  {
    return view('manager.salesman.index');
  }

  public function getSalesman(Request $request)
  {
    $query = Salesman::query();

    if ($request->has('search')) {
      $query->where('name', 'like', '%' . $request->search . '%');
    }

    $salesmans = $query->paginate(5);

    return response()->json($salesmans);
  }

  public function create()
  {
    return view('manager.salesman.create');
  }

  public function store(StoreSalesmanRequest $request)
  {
    $validatedData = $request->validated();

    Salesman::create($validatedData);

    toast('Sales berhasil ditambahkan', 'success');
    return redirect()->route('manager.salesman.index')->with('success', 'Sales berhasil ditambahkan');
  }

  public function edit(Salesman $salesman)
  {
    return view('manager.salesman.edit', compact('salesman'));
  }

  public function update(UpdateSalesmanRequest $request, Salesman $salesman)
  {
    $validatedData = $request->validated();

    $salesman->update($validatedData);

    toast('Sales berhasil diupdate', 'success');
    return redirect()->route('manager.salesman.index')->with('success', 'Sales berhasil diupdate');
  }

  public function destroy(Salesman $salesman)
  {
    $salesman->delete();

    toast('Sales berhasil dihapus', 'success');
    return redirect()->route('manager.salesman.index')->with('success', 'Sales berhasil dihapus');
  }

  /**
     * Display a listing of deleted resources.
     */
    public function deletedView()
    {
        $deletedSalesman = Salesman::onlyTrashed()->get();

        return view('manager.salesman.deleted', compact('deletedSalesman'));
    }
    /**
     * Restore the specified resource from storage.
     */
    public function restore($id)
    {
        $item = Salesman::onlyTrashed()->findOrFail($id);
        $item->restore();

        toast('Restore Success!', 'success');
        return redirect()->route('manager.salesman.index');
    }

  public function salesBySalesman()
  {
    $salesmans = Salesman::all();
    return view('manager.report.sale_by_salesman', compact('salesmans'));
  }
}
