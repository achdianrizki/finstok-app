<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class ReturnPurchaseController extends Controller
{
    public function index()
    {
        $purchase_items = Purchase::with(['item'])->get();

        return view('manager.return.purchase.index', compact('purchase_items'));
    }

    public function getPurchaseItem(Request $request)
    {
        $query = Purchase::with(['supplier'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('purchase_number', 'like', '%' . $search . '%')
                    ->orWhereHas('supplier', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('purchase_date', 'like', '%' . $search . '%');
            });
        }

        $products = $query->paginate(5);

        return response()->json($products);
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('supplier');
        $warehouses = Warehouse::all();
        $suppliers = Supplier::all();
        $categories = Category::all();
        $items = Item::all();

        return view('manager.return.purchase.create', compact('purchase', 'warehouses', 'suppliers', 'categories', 'items'));
    }
}
