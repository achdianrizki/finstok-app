<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Item;
use App\Models\Sale;
use Illuminate\Http\Request;
use App\Models\IncomingPayment;
use Illuminate\Support\Facades\DB;
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
        $query = Sale::with(['buyer'])->latest();

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

        $totalPaid = $latestPayment ? $latestPayment->total_paid + (float)str_replace(',', '.', str_replace('.', '', $request->pay_amount)) : (float) str_replace(',', '.', str_replace('.', '', $request->pay_amount));

        // Kalo gamau dinamis
        $remainingAmount = (float) str_replace(['Rp', '.', ','], ['', '', '.'], $request->remaining_payment);

        $sale = Sale::find($request->sale_id);

        $date = Carbon::parse($request->payment_date);
        $year = $date->year;
        $month = str_pad($date->month, 2, '0', STR_PAD_LEFT);

        $lastNumber = incomingPayment::whereYear('payment_date', $year)
            ->whereMonth('payment_date', $date->month)
            ->latest('invoice_number')
            ->value('invoice_number');

        $newNumber = $lastNumber ? (int) substr($lastNumber, -7, 3) + 1 : 1;

        $invoice_number = 'SEVENA' . $month . str_pad($newNumber, 4, '0', STR_PAD_LEFT) . $year;

        $payment_date = \Carbon\Carbon::createFromFormat('d-m-Y', $request->payment_date)->format('Y-m-d');

        IncomingPayment::create([
            'sale_id' => $request->sale_id,
            'invoice_number' => $invoice_number,
            'payment_date' => $payment_date,
            'payment_method' => $request->payment_method,
            'bank_account_number' => $request->bank_account_number,
            'payment_code' => $request->payment_code,
            'pay_amount' => (float) str_replace(',', '.', str_replace('.', '', $request->pay_amount)),
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

    public function exportOneInvoicePDF(IncomingPayment $incomingPayment)
    {
        $incomingPayment->load('sale');

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        // Render Blade dengan data
        $html = View::make('exports.incomingPayment.onePdf', compact('incomingPayment'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('Pembayaran_' . $incomingPayment->invoice_number . '.pdf', ['Attachment' => false]);
    }

    public function roundTotalPrice(Request $request)
    {
        $updatePurchase = DB::table('sales')
            ->where('id', $request->id)
            ->update([
                'total_price' => floatval(str_replace(['Rp', '.', ','], ['', '', '.'], $request->total_price)),
                'updated_at' => now()
            ]);

        if ($updatePurchase) {
            return response()->json(['message' => 'Total price berhasil diperbarui!'], 200);
        }

        return response()->json(['message' => 'Total price gagal diperbarui'], 404);
    }

    public function exportAllInvoicePDF(Sale $sale)
    {
        // Ambil data sale beserta incomingPayments
        $sale->load('incomingPayments');

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        // Render Blade dengan data
        $html = View::make('exports.incomingPayment.allPdf', compact('sale'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('Pembayaran_' . $sale->sale_number . '.pdf', ['Attachment' => false]);
    }
}
