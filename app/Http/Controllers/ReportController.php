<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Item;
use App\Models\Sale;
use App\Models\Buyer;
use App\Models\Purchase;
use App\Models\Salesman;
use App\Models\Supplier;
use App\Models\Warehouse;
use App\Models\ReturnSale;
use App\Exports\ItemsExport;
use Illuminate\Http\Request;
use App\Exports\BuyersExport;
use App\Models\ReturnPurchase;
use App\Models\OutgoingPayment;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SaleItemsExport;
use App\Exports\SalesmansExport;
use App\Exports\SuppliersExport;
use App\Exports\WarehousesExport;
use Illuminate\Support\Facades\DB;
use App\Exports\PurchaseItemsExport;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ItemsWarehouseExport;
use App\Exports\IncomingPaymentExport;
use App\Exports\OutgoingPaymentExport;
use App\Exports\ReturnSaleItemsExport;
use App\Exports\SalesBySalesmanExport;
use App\Exports\ReturnPurchaseItemsExport;
use App\Exports\ItemsWarehouseOpnameExport;
use App\Exports\MutationExport;

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
            ->select('return_purchases.*', 'purchases.*', 'suppliers.name as supplier_name', 'return_purchase_items.*')
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

    public function exportItemsPDF()
    {
        $items = Item::all();

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.items.pdf', compact('items'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        return $dompdf->stream('data_barang.pdf', ['Attachment' => false]);
    }

    public function exportItemsExcel()
    {
        return Excel::download(new ItemsExport, 'data_barang.xlsx');
    }

    public function exportSuppliersPDF()
    {
        $suppliers = Supplier::all();

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.suppliers.pdf', compact('suppliers'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        return $dompdf->stream('data_pemasok_barang.pdf', ['Attachment' => false]);
    }

    public function exportSuppliersExcel()
    {
        return Excel::download(new SuppliersExport, 'data_pemasok_barang.xlsx');
    }

    public function exportBuyersPDF()
    {
        $buyers = Buyer::all();

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.buyers.pdf', compact('buyers'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        return $dompdf->stream('data_pelanggan.pdf', ['Attachment' => false]);
    }

    public function exportBuyersExcel()
    {
        return Excel::download(new BuyersExport, 'data_pelanggan.xlsx');
    }

    public function exportSalesmansPDF()
    {
        $salesmans = Salesman::all();

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.salesmans.pdf', compact('salesmans'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        return $dompdf->stream('data_sales.pdf', ['Attachment' => false]);
    }

    public function exportSalesmansExcel()
    {
        return Excel::download(new SalesmansExport, 'data_sales.xlsx');
    }

    public function exportWarehousesPDF()
    {
        $warehouses = Warehouse::all();

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.warehouse.pdf', compact('warehouses'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        return $dompdf->stream('data_gudang.pdf', ['Attachment' => false]);
    }

    public function exportWarehousesExcel()
    {
        return Excel::download(new WarehousesExport, 'data_gudang.xlsx');
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

    public function exportSaleItemsExcel(Request $request)
    {
        $period = $request->period;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        return Excel::download(
            new SaleItemsExport($period, $startDate, $endDate),
            'data_penjualan.xlsx'
        );
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
        return $dompdf->stream('data_pembelian.pdf', ['Attachment' => false]);
        // return view('exports.report.sale', compact('sale'));
    }

    public function exportPurchaseItemsExcel(Request $request)
    {
        $period = $request->period;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        return Excel::download(
            new PurchaseItemsExport($period, $startDate, $endDate),
            'data_pembelian.xlsx'
        );
    }

    public function exportItemsWarehouseOpnamePDF($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        // Ambil semua item yang ada di warehouse tersebut
        $items = $warehouse->item_warehouse()->withPivot('stock', 'price_per_item')->get();

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.warehouse.itemWarehouseOpname', compact('items', 'warehouse'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        // return $dompdf->stream('SEVENA/SALE/' . str_replace('/', '_', $sale->sale_number) . '.pdf');
        return $dompdf->stream('item-warehouse-opname.pdf', ['Attachment' => false]);
        // return view('exports.report.sale', compact('sale'));
    }

    public function exportitemsWarehouseOpnameExcel($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        return Excel::download(new ItemsWarehouseOpnameExport($id), 'stok_barang_opname_di_' . $warehouse->name . '.xlsx');
    }

    public function exportItemsWarehousePDF($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        // Ambil semua item yang ada di warehouse tersebut
        $items = $warehouse->item_warehouse()->withPivot('stock', 'price_per_item')->get();

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.warehouse.itemWarehouse', compact('items', 'warehouse'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        // return $dompdf->stream('SEVENA/SALE/' . str_replace('/', '_', $sale->sale_number) . '.pdf');
        return $dompdf->stream('item-warehouse.pdf', ['Attachment' => false]);
        // return view('exports.report.sale', compact('sale'));
    }

    public function exportitemsWarehouseExcel($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return Excel::download(new ItemsWarehouseExport($id), 'stok_barang_di_' . $warehouse->name . '.xlsx');
    }

    public function exportReturnPurchaseItemsPDF()
    {

        $query = ReturnPurchase::with('items', 'purchase');

        $returnPurchases = $query->get();

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.report.returnPurchase', compact('returnPurchases'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        // return $dompdf->stream('SEVENA/SALE/' . str_replace('/', '_', $sale->sale_number) . '.pdf');
        return $dompdf->stream('returnPurchases.pdf', ['Attachment' => false]);
        // return view('exports.report.sale', compact('sale'));
    }

    public function exportReturnPurchaseItemsExcel(Request $request)
    {
        // $period = $request->period;
        // $startDate = $request->start_date;
        // $endDate = $request->end_date;

        return Excel::download(new ReturnPurchaseItemsExport, 'data_retur_pembelian.xlsx');
    }

    public function exportReturnSaleItemsPDF()
    {

        $query = ReturnSale::with('items', 'sale', 'buyer', 'salesman');

        $returnSales = $query->get();

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.report.returnSale', compact('returnSales'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        // return $dompdf->stream('SEVENA/SALE/' . str_replace('/', '_', $sale->sale_number) . '.pdf');
        return $dompdf->stream('returnSales.pdf', ['Attachment' => false]);
        // return view('exports.report.sale', compact('sale'));
    }

    public function exportReturnSaleItemsExcel(Request $request)
    {
        // $period = $request->period;
        // $startDate = $request->start_date;
        // $endDate = $request->end_date;

        return Excel::download(new ReturnSaleItemsExport, 'data_retur_penjualan.xlsx');
    }

    public function exportSalesBySalesmanPDF(Request $request)
    {
        $salesman_id = $request->salesman_id;

        $query = Sale::with('salesman', 'items');

        if ($salesman_id) {
            $query->where('salesman_id', $salesman_id);
        }

        $sales = $query->get();

        $salesman = $salesman_id ? Salesman::findOrFail($salesman_id) : null;

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.report.salebySalesman', compact('sales', 'salesman'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait'); // Menggunakan ukuran standar A4
        $dompdf->render();

        return $dompdf->stream('sale-by-salesman.pdf', ['Attachment' => false]);
    }

    public function exportSalesBySalesmanExcel(Request $request)
    {
        $salesman_id = $request->salesman_id_excel;

        return Excel::download(new SalesBySalesmanExport($salesman_id), 'data_penjualan_sales.xlsx');
    }

    public function exportOutgoingPaymentPDF()
    {
        $purchases = Purchase::with(['outgoingPayments', 'supplier'])->get();

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.outgoingPayment.pdf', compact('purchases'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        return $dompdf->stream('data_pelunasan_pembelian.pdf', ['Attachment' => false]);
    }

    public function exportOutgoingPaymentExcel()
    {
        return Excel::download(new OutgoingPaymentExport, 'data_pelunasan_pembelian.xlsx');
    }

    public function exportIncomingPaymentPDF()
    {
        $sales = Sale::with(['incomingPayments', 'buyer', 'salesman'])->get();

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.incomingPayment.pdf', compact('sales'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        return $dompdf->stream('data_pelunasan_penjualan.pdf', ['Attachment' => false]);
    }

    public function exportIncomingPaymentExcel()
    {
        return Excel::download(new IncomingPaymentExport, 'data_pelunasan_penjualan.xlsx');
    }

    public function mutation()
    {
        return view('manager.report.mutation');
    }

    public function exportMutationPDF(Request $request)
    {
        $period = $request->period;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $query = DB::table('mutations')
            ->join('items', 'mutations.item_id', '=', 'items.id')
            ->join('warehouses as from_warehouse', 'mutations.from_warehouse_id', '=', 'from_warehouse.id')
            ->join('warehouses as to_warehouse', 'mutations.to_warehouse_id', '=', 'to_warehouse.id')
            ->select('mutations.*', 'items.name as item_name', 'from_warehouse.name as from_warehouse_name', 'to_warehouse.name as to_warehouse_name');

        if ($period === 'day') {
            $query->whereDate('mutation_date', now()->toDateString());
        } elseif ($period === 'month') {
            $query->whereMonth('mutation_date', now()->month)
                ->whereYear('mutation_date', now()->year);
        } elseif ($period === 'custom' && $startDate && $endDate) {
            $query->whereBetween('mutation_date', [$startDate, $endDate]);
        }

        $mutations = $query->get();

        $options = new Options();
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);

        $html = View::make('exports.report.mutation', compact('mutations'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 595.28, 5000]); // 595.28px = A4 width, 2000px = custom height
        $dompdf->render();

        return $dompdf->stream('data_mutasi_barang.pdf', ['Attachment' => false]);
    }

    public function exportMutationExcel()
    {
        return Excel::download(new MutationExport, 'data_mutasi_barang.xlsx');
    }
}
