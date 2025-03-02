<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Item;
use App\Models\Sale;
use App\Models\Buyer;
use App\Models\Salesman;
use App\Models\Distributor;
use Illuminate\Http\Request;
use App\Models\IncomingPayment;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use App\Http\Requests\StoreSaleRequest;
use function PHPUnit\Framework\isEmpty;
use App\Http\Requests\UpdateSaleRequest;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('manager.finance.sales.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // SELECT ITEM
        $items = Item::all();
        $buyers = Buyer::all();
        $salesmans = Salesman::all();


        return view('manager.finance.sales.create', compact('items', 'buyers', 'salesmans'));
    }

    public function searchItem(Request $request)
    {
        $query = Item::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        $items = $query->get();
        return response()->json(['data' => $items]);
    }


    public function getSales()
    {
        $sales = Sale::with(['item', 'distributor', 'buyer'])
            ->paginate(10);

        return response()->json($sales);
    }

    public function getSaleItems(Request $request)
    {
        $query = Sale::with(['buyer']);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('sale_number', 'like', '%' . $search . '%')
                    ->orWhereHas('buyer', function ($query) use ($search) {
                        $query->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhere('sale_date', 'like', '%' . $search . '%');
            });
        }

        $products = $query->paginate(5);

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $lastSale = Sale::latest()->first();

        if ($lastSale) {
            $lastNumber = (int) str_replace('SAVENA/SALE/', '', $lastSale->sale_number);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $saleNumber = 'SAVENA/SALE/' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        $qty_sold = array_sum($request->qty_sold);

        $total_discount = $request->total_discount1 + $request->total_discount2 + $request->total_discount3;

        $sale = Sale::create([
            'buyer_id' => $request->buyer_id,
            'salesman_id' => $request->salesman_id,
            'sale_number' => $saleNumber,
            'total_price' => $request->total_price,
            'sub_total' => $request->sub_total,
            'discount1_value' => $request->total_discount1,
            'discount2_value' => $request->total_discount2,
            'discount3_value' => $request->total_discount3,
            'total_discount' => $total_discount,
            'sale_date' => $request->sale_date,
            'payment_method' => $request->payment_method,
            'tax' => $request->tax,
            'status' => $request->status,
            'information' => $request->information,
            'qty_sold' => $qty_sold,

        ]);

        foreach ($request->items as $index => $item_id) {
            $item = Item::findOrFail($item_id);
            $item = Item::findOrFail($item_id);
            $item->stock -= $request->qty_sold[$index];
            $item->save();

            $item->sales()->attach($sale->id, [
                'qty_sold' => $request->qty_sold[$index],
                'discount1' => $request->discount1[$index],
                'discount2' => $request->discount2[$index],
                'discount3' => $request->discount3[$index],
                'sale_price' => $request->sale_prices[$index],
            ]);
        }


        toast('Data penjualan berhasil disimpan', 'success');
        return redirect()->route('manager.sales.index')->with('success', 'Data penjualan berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */

    public function show() 
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $items = Item::all();
        $buyer = $sale->buyer;
        $salesman = $sale->salesman;


        // return view('manager.finance.sales', compact('sale'));
        return view('manager.finance.sales.edit', compact('sale', 'items', 'buyer', 'salesman'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        $validatedData = $request->validated();

        $additionalPayment = $validatedData['update_payment'];

        $newDownPayment = $sale->down_payment + $additionalPayment;

        $newRemainingPayment = max($sale->remaining_payment - $additionalPayment, 0);

        $paymentStatus = ($newDownPayment >= $sale->total_price) ? 'lunas' : $sale->payment_status;

        $sale->update([
            'down_payment' => $newDownPayment,
            'remaining_payment' => $newRemainingPayment,
            'payment_status' => $paymentStatus,
        ]);

        // Redirect instead of returning a view directly
        return redirect()->route('manager.sales.index')->with('success', 'Sale updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        // Hapus relasi dengan items sebelum menghapus sale
        $sale->items()->detach();

        // Hapus sale
        $sale->delete();

        toast('Data penjualan berhasil dihapus', 'success');
        return redirect()->route('manager.sales.index')->with('success', 'Data penjualan berhasil dihapus');
    }

    public function exportPDF(Sale $sale)
    {
        // Ambil data sale beserta incomingPayments
        $sale->load('incomingPayments');

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        // Render Blade dengan data
        $html = View::make('exports.sales.pdf', compact('sale'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('Pembayaran_' . $sale->sale_number . '.pdf');
    }
}
