<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SalesmanController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ReturnSaleController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\ReturnPurchaseController;
use App\Http\Controllers\incomingPaymentController;
use App\Http\Controllers\OutgoingPaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/categories/search', [CategoryController::class, 'search'])->name('categories.search');
    Route::post('/categories/storeinput', [CategoryController::class, 'storeinput'])->name('categories.storeinput');

    Route::prefix('manager')->name('manager.')->group(function () {
        Route::resource('items', ItemController::class)->middleware('role:manager|admin');
        Route::resource('warehouses', WarehouseController::class)->middleware('role:manager|admin');
        Route::resource('warehouses', WarehouseController::class)->parameters([
            'warehouses' => 'warehouse:slug'
        ])->middleware('role:manager|admin');
        Route::resource('purchase', PurchaseController::class)->middleware('role:manager|admin');

        Route::resource('sales', SaleController::class)->middleware('role:manager|admin');
        Route::resource('distributors', DistributorController::class)->middleware('role:manager|admin');
        Route::resource('users', UserController::class)->middleware('role:manager');
        Route::resource('supplier', SupplierController::class)->middleware('role:manager');
        Route::resource('outgoingpayment', OutgoingPaymentController::class)->middleware('role:manager');
        Route::get('/outgoingpayment/payment/{purchase}', [OutgoingPaymentController::class, 'create_payment'])
            ->middleware('role:manager')
            ->name('outgoingpayment.payment');

        Route::resource('incomingpayment', IncomingPaymentController::class)->middleware('role:manager');

        Route::get('/incomingpayment/payment/{id}', [IncomingPaymentController::class, 'create'])
            ->name('incomingpayment.payment');


        //Return Purchase
        Route::prefix('return')->name('return.')->group(function () {
            Route::get('/purchase', [ReturnPurchaseController::class, 'index'])->name('purchase');
            Route::get('/purchase/{purchase}', [ReturnPurchaseController::class, 'show'])->name('purchase.show');
            Route::post('/purchases/{id}/return', [ReturnPurchaseController::class, 'returnPurchase'])->name('purchase.create');

            Route::get('/sale', [ReturnSaleController::class, 'index'])->name('sale');
            Route::get('/sale/{sale}', [ReturnSaleController::class, 'show'])->name('sale.show');
            Route::post('/sale/{id}/return', [ReturnSaleController::class, 'returnSale'])->name('sale.create');
        });

        Route::resource('buyer', BuyerController::class)->middleware('role:manager|admin');
        Route::resource('salesman', SalesmanController::class)->middleware('role:manager|admin');



        Route::get('/finance/sales', [FinanceController::class, 'sales'])->name('finance.sales');

        //Modal
        Route::resource('modal', ModalController::class)->middleware('role:manager|finance');
        //Modal update status (is_confirm)
        Route::put('updateStatus/{modal}', [ModalController::class, 'updateStatus'])->name('modal.updateStatus')->middleware('role:manager');

        Route::resource('asset', AssetController::class)->middleware('role:manager|finance');

        // SALE PAYMENT
        // Route::get('/incomingPayment/create/{sale_id}', [IncomingPaymentController::class, 'create'])
        //     ->name('incomingPayment.create');

        // Route untuk menyimpan incomingPayment
        Route::post('/incomingPayment/store', [IncomingPaymentController::class, 'store'])
            ->name('incomingPayment.store')
            ->middleware('role:manager|finance');


        Route::prefix('other')->name('other.')->group(function () {
            Route::resource('categories', CategoryController::class)->middleware('role:manager|admin');
            Route::resource('distributors', DistributorController::class)->middleware('role:manager|admin');
        });

        Route::prefix('report')->name('report.')->group(function () {
            Route::get('/purchase', [ReportController::class, 'purchase'])->name('purchase');
            Route::get('/sale', [ReportController::class, 'sale'])->name('sale');
            Route::get('/purchases/return', [ReportController::class, 'returnPurchaseView'])->name('purchase.return');
            Route::get('/sale/return', [ReportController::class, 'returnSaleView'])->name('sale.return');
            Route::get('/item-warehouse/{id}', [ReportController::class, 'itemWarehouse'])->name('item.warehouse');

            // REPORT
            // ITEMS AND INVOICE START
            // Purchase invoice PDF (1 by 1)
            Route::get('/purchase-invoice/export/pdf/{id}', [ReportController::class, 'exportPurchaseInvoicePDF'])->name('purchase-invoice.export.pdf');

            // Purchase items PDF (all)
            Route::post('/purchase-items-report/export/pdf', [ReportController::class, 'exportPurchaseItemsPDF'])->name('purchase-items-report.export.pdf');

            // Sale PDF invoice (1 by 1)
            Route::get('/sale-invoice/export/pdf/{id}', [ReportController::class, 'exportSaleInvoicePDF'])->name('sale-invoice.export.pdf');

            // Sale items PDF (all)
            Route::post('/sale-items-report/export/pdf', [ReportController::class, 'exportSaleItemsPDF'])->name('sale-items-report.export.pdf');
            // ITEMS AND INVOICE END

            // PAYMENT START
            // Incoming Payment all (SALE)
            Route::get('/incomingPayment/export/allPdf/{sale}', [incomingPaymentController::class, 'exportAllInvoicePDF'])->name('incomingPayment.export.allPdf');

            // Incoming Payment ( 1 by 1) (SALE)
            Route::get('/incomingPayment/export/onePdf/{incomingPayment}', [incomingPaymentController::class, 'exportOneInvoicePDF'])->name('incomingPayment.export.onePdf');

            // Outgoing Payment all (PURCHASE)
            Route::get('/outgoingPayment/export/allPdf/{purchase}', [outgoingPaymentController::class, 'exportAllInvoicePDF'])->name('outgoingPayment.export.allPdf');

            // Incoming Payment ( 1 by 1) (PURCHASE)
            Route::get('/outgoingPayment/export/onePdf/{outgoingPayment}', [outgoingPaymentController::class, 'exportOneInvoicePDF'])->name('outgoingPayment.export.onePdf');
            // PAYMENT END
        });
    });

    //printPdf & ExportExcel
    Route::get('/items/export/pdf', [ItemController::class, 'exportPDF'])->name('items.export.pdf');
    Route::get('/items/export/excel', [ItemController::class, 'exportExcel'])->name('items.export.excel');

    //Testing total modal
    Route::get('/manager/finance/primaryModal', [ModalController::class, 'primaryModal'])->name('manager.finance.modal.primaryModal');
});

//Fungsi get data ajaxx
Route::get('/items-data', [ItemController::class, 'getItems']);
Route::get('/get-items', [PurchaseController::class, 'getItemsPurchase']);
Route::get('/categories-data', [CategoryController::class, 'getCategories']);
Route::get('/warehouses-data', [WarehouseController::class, 'getWarehouses']);
Route::get('/manager/warehouses/{warehouse:id}/items', [WarehouseController::class, 'getItemsByWarehouse']);
Route::get('/distributors-data', [DistributorController::class, 'getDistributors']);
Route::get('/purchases-data', [PurchaseController::class, 'getPurchaseItem']);
Route::get('/outgoingpayment-data', [OutgoingPaymentController::class, 'getPurchaseItem']);
Route::get('/sales-data', [SaleController::class, 'getSaleItem']);
Route::get('/items-data-sale', [SaleController::class, 'searchItem']);
Route::get('/users-data', [UserController::class, 'getUsers'])->name('manager.users.data');
Route::get('/supplier-data', [SupplierController::class, 'getSupplier'])->name('manager.users.data');
Route::get('/sales-data', [SaleController::class, 'getSaleItems']);

Route::get('/assets-data', [AssetController::class, 'getAssets']);
Route::get('/buyers-data', [BuyerController::class, 'getBuyers']);
Route::get('/salesmans-data', [SalesmanController::class, 'getSalesman']);

Route::get('/return-purchase-data', [ReturnPurchaseController::class, 'getPurchaseItem']);
Route::get('/return-sale-data', [ReturnSaleController::class, 'getSaleItem']);

Route::get('/laporan/laba-rugi', [ChartController::class, 'getLabaRugi']);

Route::post('/adjust-stock', [WarehouseController::class, 'adjustStock']);
Route::get('/get-item/{item_id}/{supplier_id}', function ($item_id, $supplier_id) {
    $item = \App\Models\Item::find($item_id);
    $supplier = \App\Models\Supplier::find($supplier_id);

    return response()->json([
        'code' => $item->code,
        'name' => $item->name,
        'stock' => $item->stock,
        'unit' => $item->unit,
        'price' => $item->price,
        'purchase_price' => $item->purchase_price,
    ]);
});

Route::get('/buyers-data', [BuyerController::class, 'getBuyers']);
Route::get('/salesmans-data', [SalesmanController::class, 'getSalesman']);
Route::get('/laporan/laba-rugi', [ChartController::class, 'getLabaRugi']);
Route::get('/get-sales-item/{item_id}', function ($item_id) {
    $item = \App\Models\Item::find($item_id);

    return response()->json([
        'code' => $item->code,
        'name' => $item->name,
        'stock' => $item->stock,
        'unit' => $item->unit,
        'price' => $item->price,
        'purchase_price' => $item->purchase_price,
        'discount1' => 0,
        'discount2' => 0,
        // 'discount1' => $supplier ? $supplier->discount1 : 0,
        // 'discount2' => $supplier ? $supplier->discount2 : 0,
    ]);
});

// FUNGSI DELETE DATA ITEM PEMBELIAN AJAXXX
Route::delete('/purchase-edit/{purchase}/item-delete/{item}', [PurchaseController::class, 'deleteItem']);

Route::get('/incomingpayment-data', [incomingPaymentController::class, 'getSaleItem']);

require __DIR__ . '/auth.php';
