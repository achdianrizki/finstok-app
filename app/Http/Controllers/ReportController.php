<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class ReportController extends Controller
{
    public function purchase()
    {
        return view('manager.report.purchase');
    }

    public function sale()
    {
        return view('manager.report.sale');
    }

    public function returnPurchaseView()
    {
        $return = DB::table('return_purchases')
            ->join('purchases', 'return_purchases.purchase_id', '=', 'purchases.id')
            ->join('suppliers', 'return_purchases.supplier_id', '=', 'suppliers.id')
            ->join('return_purchase_items', 'return_purchases.id', '=', 'return_purchase_items.return_purchase_id')
            ->select('return_purchases.*', 'purchases.*', 'suppliers.contact as supplier_name', 'return_purchase_items.*')
            ->get();
        return view('manager.report.purchase_return', compact('return'));
    }

    public function returnSaleView()
    {
        $return = DB::table('return_sales')
            ->join('sales', 'return_sales.sale_id', '=', 'sales.id')
            ->join('buyers', 'return_sales.buyer_id', '=', 'buyers.id')
            ->join('return_sale_items', 'return_sales.id', '=', 'return_sale_items.return_sale_id')
            ->select('return_sales.*', 'sales.*', 'buyers.contact as buyer_contact', 'return_sale_items.*')
            ->get();
        return view('manager.report.sale_return', compact('return'));
    }

    public function exportPurchaseInvoicePDF($id)
    {
        $purchase = Purchase::with('items')->findOrFail($id);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.invoice.purchaseInvoice', compact('purchase'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        // return $dompdf->stream('SEVENA/SALE/' . str_replace('/', '_', $purchase->sale_number) . '.pdf');
        return $dompdf->stream('purchase.pdf', ['Attachment' => false]);
        // return view('exports.report.purchase', compact('purchase'));
    }

    public function exportSaleInvoicePDF($id)
    {
        $sale = Sale::with('items')->findOrFail($id);

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.invoice.saleInvoice', compact('sale'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        // return $dompdf->stream('SEVENA/SALE/' . str_replace('/', '_', $sale->sale_number) . '.pdf');
        return $dompdf->stream('sale.pdf', ['Attachment' => false]);
        // return view('exports.report.sale', compact('sale'));
    }

    public function exportSaleItemsPDF(Request $request)
    {
        $period = $request->period;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Sale::with('items');

        if ($period === 'day') {
            $query->whereDate('sale_date', now()->toDateString());
        } elseif ($period === 'month') {
            $query->whereMonth('sale_date', now()->month)
                ->whereYear('sale_date', now()->year);
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $query->whereBetween('sale_date', [$startDate, $endDate]);
        }

        $sales = $query->get();

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.report.sale', compact('sales', 'period'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        // return $dompdf->stream('SEVENA/SALE/' . str_replace('/', '_', $sale->sale_number) . '.pdf');
        return $dompdf->stream('sale.pdf', ['Attachment' => false]);
        // return view('exports.report.sale', compact('sale'));
    }

    public function exportPurchaseItemsPDF(Request $request)
    {
        $period = $request->period;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = Purchase::with('items');

        if ($period === 'day') {
            $query->whereDate('purchase_date', now()->toDateString());
        } elseif ($period === 'month') {
            $query->whereMonth('purchase_date', now()->month)
                ->whereYear('purchase_date', now()->year);
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $query->whereBetween('purchase_date', [$startDate, $endDate]);
        }

        $purchases = $query->get();

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.report.purchase', compact('purchases', 'period'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        // return $dompdf->stream('SEVENA/SALE/' . str_replace('/', '_', $sale->sale_number) . '.pdf');
        return $dompdf->stream('purchase.pdf', ['Attachment' => false]);
        // return view('exports.report.sale', compact('sale'));
    }

    public function itemWarehouse(Warehouse $warehouse)
    {
        
    }
}
