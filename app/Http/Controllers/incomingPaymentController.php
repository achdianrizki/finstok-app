<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Item;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Models\IncomingPayment;
use Illuminate\Support\Facades\View;
use App\Http\Requests\StoreIncomingPaymentRequest;

class incomingPaymentController extends Controller
{
    public function index()
    {
        return view('manager.finance.incomingPayment.index');
    }

    public function create($id)
    {
        $sale = Sale::findOrFail($id);

        $payed_amount = $sale->incomingPayments->sum('pay_amount');

        return view('manager.finance.incomingPayment.create', compact('sale', 'payed_amount'));
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
                    ->orWhere('sale_date', 'like', '%' . $search . '%');
            });
        }

        $products = $query->paginate(5);

        return response()->json($products);
    }

    public function show($id)
    {
        $sale = Sale::findOrFail($id);
        $items = Item::all();
        $buyer = $sale->buyer;
        $salesman = $sale->salesman;
        $incomingPayments = $sale->incomingPayments;
        $total_payed = $sale->incomingPayments->sum('pay_amount');

        // Menggunakan Query Builder agar bisa pakai orderByDesc
        $last_payment = $sale->incomingPayments()->orderByDesc('created_at')->first();
        $remaining_payment = optional($last_payment)->remaining_payment ?? 0;

        return view('manager.finance.incomingPayment.payment', compact('sale', 'items', 'buyer', 'salesman', 'incomingPayments', 'total_payed', 'remaining_payment'));
    }

    public function store(Request $request)
    {
        $latestPayment = IncomingPayment::where('sale_id', $request->sale_id)->latest()->first();

        $totalPaid = $latestPayment ? $latestPayment->total_paid + (float)str_replace(',', '.', str_replace('.', '', $request->pay_amount) ) : (float) str_replace(',', '.', str_replace('.', '', $request->pay_amount) );

        // Kalo gamau dinamis
        $remainingAmount = (float) str_replace(['Rp', '.', ','], ['', '', '.'], $request->remaining_payment);

        $sale = Sale::find($request->sale_id);

        IncomingPayment::create([
            'sale_id' => $request->sale_id,
            'invoice_number' => $request->invoice_number,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'bank_account_number' => $request->bank_account_number,
            'payment_code' => $request->payment_code,
            'pay_amount' => (float) str_replace(',', '.', str_replace('.', '', $request->pay_amount) ),
            'information' => $request->information,
            // 'remaining_payment' => (float) str_replace(['Rp', '.', ','], ['', '', '.'], $request->remaining_payment),
            'remaining_payment' => $remainingAmount,
            'total_paid' => $totalPaid,
        ]);

        if ($totalPaid >= $sale->total_price) {
            $sale->update(['status' => 'Lunas']);
        }


        toast('Pembayaran berhasil ditambahkan', 'success');
        return redirect()->route('manager.incomingpayment.show', $request->sale_id)->with('success', 'Pembayaran berhasil ditambahkan');
    }

    public function exportPDF(IncomingPayment $incomingPayment)
    {
        $incomingPayment->load('sale');

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        // Render Blade dengan data
        $html = View::make('exports.incomingPayment.pdf', compact('incomingPayment'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('Pembayaran_' . $incomingPayment->invoice_number . '.pdf');
    }
}
