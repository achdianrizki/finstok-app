<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Sale;
use Illuminate\Http\Request;

class ReturnSaleController extends Controller
{
    public function index()
    {
        return view('manager.return.sale.index');
    }

    public function getSaleItem(Request $request)
    {
        $query = Sale::with(['buyer']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', '%' . $search . '%')
                    ->orWhereHas('buyer', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('sale_date', 'like', '%' . $search . '%')->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        $products = $query->paginate(5);

        return response()->json($products);
    }

    public function show(Sale $sale)
    {
        $items = Item::all();
        $buyer = $sale->buyer;
        $salesman = $sale->salesman;


        // return view('manager.finance.sales', compact('sale'));
        return view('manager.return.sale.create', compact('sale', 'items', 'buyer', 'salesman'));
    }
}
