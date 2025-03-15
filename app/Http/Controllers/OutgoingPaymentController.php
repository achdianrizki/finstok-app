<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Item;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\outgoingPayment;
use Illuminate\Support\Facades\View;

class outgoingPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('manager.outgoingPayment.index');
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

    /**
     * Show the form for creating a new resource.
     */
    public function create_payment(Purchase $purchase)
    {
        $suppliers = $purchase->supplier;

        $payed_amount = $purchase->outgoingPayments->sum('amount_paid');
        // dd($purchase->payments->total_unpaid);
        return view('manager.outgoingPayment.show', compact('suppliers', 'purchase', 'payed_amount'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $latestPayment = OutgoingPayment::where('purchase_id', $request->purchase_id)->latest()->first();

        $totalPaid = $latestPayment ? $latestPayment->total_paid + (float)str_replace(',', '.', str_replace('.', '', $request->amount_paid)) : (float) str_replace(',', '.', str_replace('.', '', $request->amount_paid));

        // Kalo gamau dinamis
        $total_unpaid = (float) str_replace(['Rp', '.', ','], ['', '', '.'], $request->total_unpaid);

        $purchase = Purchase::find($request->purchase_id);

        OutgoingPayment::create([
            'purchase_id' => $request->purchase_id,
            'receipt_number' => $request->receipt_number,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'bank_account_number' => $request->bank_account_number,
            'payment_code' => $request->payment_code,
            'amount_paid' => (float) str_replace(',', '.', str_replace('.', '', $request->amount_paid)),
            'note' => $request->note,
            // 'remaining_payment' => (float) str_replace(['Rp', '.', ','], ['', '', '.'], $request->remaining_payment),
            'total_unpaid' => $total_unpaid,
            'total_paid' => $totalPaid,
        ]);

        if ($totalPaid >= $purchase->total_price) {
            $purchase->update(['status' => 'Lunas']);
        }


        toast('Pembayaran berhasil ditambahkan', 'success');
        return redirect()->route('manager.outgoingpayment.show', $request->purchase_id)->with('success', 'Pembayaran berhasil ditambahkan');
    }





    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $purchase = Purchase::with('payments', 'supplier')->findOrFail($id);
        $outgoingPayments = $purchase->outgoingPayments;
        $total_payed = $purchase->outgoingPayments->sum('pay_amount');

        // Menggunakan Query Builder agar bisa pakai orderByDesc
        $last_payment = $purchase->outgoingPayments()->orderByDesc('created_at')->first();
        $total_unpaid = optional($last_payment)->total_unpaid ?? 0;



        // dd($items);

        return view('manager.outgoingPayment.payment', compact('purchase', 'outgoingPayments', 'total_payed', 'last_payment', 'total_unpaid'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $suppliers = $purchase->supplier;
        dd($purchase, $suppliers);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function exportOneInvoicePDF(OutgoingPayment $outgoingPayment)
    {
        $outgoingPayment->load('purchase');

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        // Render Blade dengan data
        $html = View::make('exports.outgoingPayment.onePdf', compact('outgoingPayment'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('Pembayaran_' . $outgoingPayment->invoice_number . '.pdf');
    }

    public function exportAllInvoicePDF(Purchase $purchase)
    {
        $purchase->load('outgoingPayments');

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf = new Dompdf($options);

        // Render Blade dengan data
        $html = View::make('exports.outgoingPayment.allPdf', compact('purchase'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('Pembayaran_' . $purchase->purchase_number . '.pdf');
    }
}
