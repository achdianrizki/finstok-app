<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
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

    public function exportPurchasePDF()
    {
        $purchase = Purchase::with('items');

        $items = $purchase->items->map(function ($item) {
            return [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'unit' => $item->unit,
                'qty' => $item->pivot->qty,
                'purchase_price' => $item->purchase_price,
                'price_per_item' => $item->pivot->price_per_item,
                'discount1' => $item->pivot->discount1,
                'discount2' => $item->pivot->discount2,
                'discount3' => $item->pivot->discount3,
                'ad' => $item->pivot->ad,
                'total_price_before_discount' => $item->pivot->sub_total,
                'total_price_after_discount' => $item->pivot->total_price_after_discount,
            ];
        });

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.report.purchase', compact('items', 'purchase'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('Invoice_' . str_replace('/', '_', $purchase->purchase_number) . '.pdf');
    }
}
