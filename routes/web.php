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
use App\Http\Controllers\MutationController;
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
        Route::get('/warehouse-opname/{warehouse:slug}', [WarehouseController::class, 'opname'])
            ->middleware('role:manager|admin')->name('warehouses.opname');
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


        //Return Purchase START
        Route::prefix('return')->name('return.')->group(function () {
            Route::get('/purchase', [ReturnPurchaseController::class, 'index'])->name('purchase');
            Route::get('/purchase/{purchase}', [ReturnPurchaseController::class, 'show'])->name('purchase.show');
            Route::post('/purchases/{id}/return', [ReturnPurchaseController::class, 'returnPurchase'])->name('purchase.create');

            Route::get('/sale', [ReturnSaleController::class, 'index'])->name('sale');
            Route::get('/sale/{sale}', [ReturnSaleController::class, 'show'])->name('sale.show');
            Route::post('/sale/{id}/return', [ReturnSaleController::class, 'returnSale'])->name('sale.create');
            //Return Purchase END
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

            Route::get('/item-warehouse-opname/{id}/export/pdf', [ReportController::class, 'exportItemsWarehouseOpnamePDF'])->name('items.warehouse.opname.export.pdf');
            Route::get('/item-warehouse-opname/{id}/export/excel', [ReportController::class, 'exportItemsWarehouseOpnameExcel'])->name('items.warehouse.opname.export.excel');

            Route::get('/item-warehouse/{id}/export/pdf', [ReportController::class, 'exportItemsWarehousePDF'])->name('items.warehouse.export.pdf');
            Route::get('/item-warehouse/{id}/export/excel', [ReportController::class, 'exportItemsWarehouseExcel'])->name('items.warehouse.export.excel');

            // Sale Data by salesman START (VIEW)
            Route::get('/sales-by-salesman', [SalesmanController::class, 'salesBySalesman'])->name('sales-by-salesman');
            // Sale Data by salesman END (VIEW)


            // REPORT
            // MASTER START
            // ITEMS
            Route::get('/items/export/pdf', [ReportController::class, 'exportItemsPDF'])->name('items.export.pdf');
            Route::get('/items/export/excel', [ReportController::class, 'exportItemsExcel'])->name('items.export.excel');

            // SUPPLIERS
            Route::get('/suppliers/export/pdf', [ReportController::class, 'exportSuppliersPDF'])->name('suppliers.export.pdf');
            Route::get('/suppliers/export/excel', [ReportController::class, 'exportSuppliersExcel'])->name('suppliers.export.excel');

            // BUYERS
            Route::get('/buyers/export/pdf', [ReportController::class, 'exportBuyersPDF'])->name('buyers.export.pdf');
            Route::get('/buyers/export/excel', [ReportController::class, 'exportBuyersExcel'])->name('buyers.export.excel');

            // SALESMAN
            Route::get('/salesmans/export/pdf', [ReportController::class, 'exportSalesmansPDF'])->name('salesmans.export.pdf');
            Route::get('/salesmans/export/excel', [ReportController::class, 'exportSalesmansExcel'])->name('salesmans.export.excel');

            // WAREHOUSE
            Route::get('/warehouses/export/pdf', [ReportController::class, 'exportWarehousesPDF'])->name('warehouses.export.pdf');
            Route::get('/warehouses/export/excel', [ReportController::class, 'exportWarehousesExcel'])->name('warehouses.export.excel');
            // MASTER END

            // ITEMS AND INVOICE START
            // Purchase invoice (FAKTUR) PDF (1 by 1)
            Route::get('/purchase-invoice/export/pdf/{id}', [ReportController::class, 'exportPurchaseInvoicePDF'])->name('purchase-invoice.export.pdf');

            // Purchase items PDF (all)
            Route::post('/purchase-items-report/export/pdf', [ReportController::class, 'exportPurchaseItemsPDF'])->name('purchase-items-report.export.pdf');
            Route::post('/purchase-items-report/export/excel', [ReportController::class, 'exportPurchaseItemsExcel'])->name('purchase-items-report.export.excel');

            // Sale PDF invoice (FAKTUR) (1 by 1)
            Route::get('/sale-invoice/export/pdf/{id}', [ReportController::class, 'exportSaleInvoicePDF'])->name('sale-invoice.export.pdf');

            // Sale items PDF (all)
            Route::post('/sale-items-report/export/pdf', [ReportController::class, 'exportSaleItemsPDF'])->name('sale-items-report.export.pdf');
            Route::post('/sale-items-report/export/excel', [ReportController::class, 'exportSaleItemsExcel'])->name('sale-items-report.export.excel');
            // ITEMS AND INVOICE END

            // PAYMENT START
            // Incoming Payment all (SALE)
            Route::get('/incomingPayment/export/allPdf/{sale}', [incomingPaymentController::class, 'exportAllInvoicePDF'])->name('incomingPayment.export.allPdf');

            // REPORT INCOMING PAYMENT ALL (SALE)
            Route::get('/incomingPayment/export/pdf', [ReportController::class, 'exportIncomingPaymentPDF'])->name('incomingPayment.export.pdf');
            Route::get('/incomingPayment/export/excel', [ReportController::class, 'exportIncomingPaymentExcel'])->name('incomingPayment.export.excel');

            // Incoming Payment ( 1 by 1) (SALE)
            Route::get('/incomingPayment/export/onePdf/{incomingPayment}', [incomingPaymentController::class, 'exportOneInvoicePDF'])->name('incomingPayment.export.onePdf');

            // Outgoing Payment all (PURCHASE)
            Route::get('/outgoingPayment/export/allPdf/{purchase}', [outgoingPaymentController::class, 'exportAllInvoicePDF'])->name('outgoingPayment.export.allPdf');

            // REPORT OUTGOING PAYMENT ALL (PURCHASE)
            Route::get('/outgoingPayment/export/pdf', [ReportController::class, 'exportOutgoingPaymentPDF'])->name('outgoingPayment.export.pdf');
            Route::get('/outgoingPayment/export/excel', [ReportController::class, 'exportOutgoingPaymentExcel'])->name('outgoingPayment.export.excel');

            // IOutgoing Payment ( 1 by 1) (PURCHASE)
            Route::get('/outgoingPayment/export/onePdf/{outgoingPayment}', [outgoingPaymentController::class, 'exportOneInvoicePDF'])->name('outgoingPayment.export.onePdf');
            // PAYMENT END

            // RETURN PURCHASE (all) START
            Route::get('/return-purchase-items-report/export/pdf', [ReportController::class, 'exportReturnPurchaseItemsPDF'])->name('return-purchase-items-report.export.pdf');
            Route::get('/return-purchase-items-report/export/excel', [ReportController::class, 'exportReturnPurchaseItemsExcel'])->name('return-purchase-items-report.export.excel');
            // RETURN PURCHASE END

            // RETURN SALE (all) START
            Route::get('/return-sale-items-report/export/pdf', [ReportController::class, 'exportReturnSaleItemsPDF'])->name('return-sale-items-report.export.pdf');
            Route::get('/return-sale-items-report/export/excel', [ReportController::class, 'exportReturnSaleItemsExcel'])->name('return-sale-items-report.export.excel');
            // RETURN SALE END

            // Sale Data by salesman START (PDF)
            Route::post('/sales-by-salesman/export/pdf', [ReportController::class, 'exportSalesBySalesmanPDF'])->name('sales-by-salesman.export.pdf');
            Route::post('/sales-by-salesman/export/excel', [ReportController::class, 'exportSalesBySalesmanExcel'])->name('sales-by-salesman.export.excel');
            // Sale Data by salesman END (PDF)
        });
        
        Route::prefix('trash')->name('trash.')->group(function () {
            Route::get('/items', [ItemController::class, 'deletedView'])->name('items');
            Route::post('/items/{id}', [ItemController::class, 'restore'])->name('items.restore');

            Route::get('/supplier', [SupplierController::class, 'deletedView'])->name('supplier');
            Route::post('/supplier/{id}', [SupplierController::class, 'restore'])->name('supplier.restore');
            
            Route::get('/buyer', [BuyerController::class, 'deletedView'])->name('buyer');
            Route::post('/buyer/{id}', [BuyerController::class, 'restore'])->name('buyer.restore');

            Route::get('/salesman', [SalesmanController::class, 'deletedView'])->name('salesman');
            Route::post('/salesman/{id}', [SalesmanController::class, 'restore'])->name('salesman.restore');

            Route::get('/warehouse', [WarehouseController::class, 'deletedView'])->name('warehouse');
            Route::post('/warehouse/{id}', [WarehouseController::class, 'restore'])->name('warehouse.restore');

            Route::get('/category', [CategoryController::class, 'deletedView'])->name('category');
            Route::post('/category/{id}', [CategoryController::class, 'restore'])->name('category.restore');

            

            Route::get('/purchase', [PurchaseController::class, 'deletedView'])->name('purchase');
            Route::post('/purchase/{id}', [PurchaseController::class, 'restore'])->name('purchase.restore');

            Route::get('/sale', [SaleController::class, 'deletedView'])->name('sale');
            Route::post('/sale/{id}', [SaleController::class, 'restore'])->name('sale.restore');

        });
    });

    Route::prefix('mutation')->name('mutation.')->group(function () {
        Route::get('/get-warehouse', [MutationController::class, 'getWarehouse'])->name('get-warehouses');
        Route::post('/store', [MutationController::class, 'store'])->name('store');
    });


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
Route::get('/supplier-data', [SupplierController::class, 'getSupplier']);
Route::get('/sales-data', [SaleController::class, 'getSaleItems']);
Route::get('/sales-by-salesman-data', [SaleController::class, 'getSaleItemsBySalesman']);

Route::get('/assets-data', [AssetController::class, 'getAssets']);
Route::get('/buyers-data', [BuyerController::class, 'getBuyers']);
Route::get('/salesmans-data', [SalesmanController::class, 'getSalesman']);

Route::get('/return-purchase-data', [ReturnPurchaseController::class, 'getPurchaseItem']);
Route::get('/return-sale-data', [ReturnSaleController::class, 'getSaleItem']);

Route::get('/laporan/laba-rugi', [ChartController::class, 'getLabaRugi']);

Route::post('/adjust-stock', [WarehouseController::class, 'adjustStock']);
Route::get('/get-items/{supplier}', [SupplierController::class, 'getItemsBySupplier']);

// PEMBULATAN TOTAL PRICE DI PURCHASE PAYMENT (OUTGOING PAYMENT)
Route::post('/purchase-round-total-price', [OutgoingPaymentController::class, 'roundTotalPrice']);
// PEMBULATAN TOTAL PRICE DI SALE PAYMENT (INCOMING PAYMENT)
Route::post('/sale-round-total-price', [IncomingPaymentController::class, 'roundTotalPrice']);

Route::get('/get-item/{item_id}/{supplier_id}', function ($item_id, $supplier_id) {
    $item = \App\Models\Item::withTrashed()->find($item_id);
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

Route::post('/get-items/warehouse', [WarehouseController::class, 'getItemsByWarehouses']);
Route::get('/check-warehouse-items/{warehouse_id}', [WarehouseController::class, 'checkItems']);
Route::get('/get-sales-item/{item_id}/{warehouse_id}', function ($item_id, $warehouse_id) {
    $item = \App\Models\Item::find($item_id);
    $warehouseStock = \DB::table('item_warehouse')
        ->where('item_id', $item_id)
        ->where('warehouse_id', $warehouse_id)
        ->value('stock') ?? 0;

    return response()->json([
        'code' => $item->code,
        'name' => $item->name,
        'stock' => $warehouseStock,
        'unit' => $item->unit,
        'price' => $item->price,
        'purchase_price' => $item->purchase_price,
        'discount1' => 0,
        'discount2' => 0,
    ]);
});


// FUNGSI DELETE DATA ITEM PEMBELIAN AJAXXX
Route::delete('/purchase-edit/{purchase}/item-delete/{item}', [PurchaseController::class, 'deleteItem']);
Route::delete('/sale-edit/{sale}/item-delete/{item}', [SaleController::class, 'deleteItem']);

Route::get('/incomingpayment-data', [incomingPaymentController::class, 'getSaleItem']);

require __DIR__ . '/auth.php';
