<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index () {
        return view('manager.finance.asset.index');
    }

    public function getAssets(Request $request)
    {
        $query = Asset::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $assets = $query->paginate(5);

        return response()->json($assets);
    }

    public function create()
    {
        return view('manager.finance.asset.create');
    }

    public function store(StoreAssetRequest $request)
    {
        $validatedData = $request->validated();

        Asset::create($validatedData);

        toast('Aset berhasil ditambahkan', 'success');
        return redirect()->route('manager.asset.index')->with('success', 'Aset berhasil ditambahkan');
    }

    public function edit(Asset $asset)
    {
        return view('manager.finance.asset.edit', compact('asset'));
    }

    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $validatedData = $request->validated();
        
        $asset->update($validatedData);

        toast('Aset berhasil diupdate', 'success');
        return redirect()->route('manager.asset.index')->with('success','Aset berhasil diupdate');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();

        toast('Aset berhasil dihapus', 'success');
        return redirect()->route('manager.asset.index')->with('success', 'Aset berhasil dihapus');
    }
}
