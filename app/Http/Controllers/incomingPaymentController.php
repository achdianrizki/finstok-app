<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Models\IncomingPayment;
use Illuminate\Support\Facades\View;
use App\Http\Requests\StoreIncomingPaymentRequest;

class incomingPaymentController extends Controller
{
    public function create($sale_id)
    {
        $sale = Sale::findOrFail($sale_id);

        $payed_amount = $sale->incomingPayments->sum('pay_amount');

        return view('manager.finance.incomingPayment.create', compact('sale', 'payed_amount'));
    }

    public function store(Request $request)
    {
        $latestPayment = IncomingPayment::where('sale_id', $request->sale_id)->latest()->first();

        $totalPaid = $latestPayment ? $latestPayment->total_paid + $request->pay_amount : $request->pay_amount;

        $sale = Sale::find($request->sale_id);

        IncomingPayment::create([
            'sale_id' => $request->sale_id,
            'invoice_number' => $request->invoice_number,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'bank_account_number' => $request->bank_account_number,
            'payment_code' => $request->payment_code,
            'pay_amount' => $request->pay_amount,
            'information' => $request->information,
            'remaining_payment' => $request->remaining_payment,
            'total_paid' => $totalPaid,
        ]);

        if ($totalPaid >= $sale->total_price) {
            $sale->update(['status' => 'Lunas']);
        }


        toast('Pembayaran berhasil ditambahkan', 'success');
        return redirect()->route('manager.sales.show', $request->sale_id)->with('success', 'Pembayaran berhasil ditambahkan');
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
