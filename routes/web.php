<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ModalController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\PurchaseController;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/categories/search', [CategoryController::class, 'search'])->name('categories.search');
    Route::get('/categories/search', [CategoryController::class, 'search'])->name('categories.search');
    Route::post('/categories/storeinput', [CategoryController::class, 'storeinput'])->name('categories.storeinput');

    Route::prefix('manager')->name('manager.')->group(function () {
        Route::resource('categories', CategoryController::class)->middleware('role:manager|admin');
        Route::resource('items', ItemController::class)->middleware('role:manager|admin');
        Route::resource('distributors', DistributorController::class)->middleware('role:manager|admin');
        Route::resource('warehouse', WarehouseController::class)->middleware('role:manager|admin');


        Route::get('/purchase/item', [PurchaseController::class, 'item_purchase'])->name('finance.item-purchase');
        Route::get('/purchase/other', [PurchaseController::class, 'other_purchase'])->name('finance.other-purchase');


        Route::get('/finance/sales', [FinanceController::class, 'sales'])->name('finance.sales');

        //Modal
        Route::resource('modal', ModalController::class)->middleware('role:manager|finance');
        //Modal update status (is_confirm)
        Route::put('updateStatus/{modal}', [ModalController::class, 'updateStatus'])->name('modal.updateStatus')->middleware('role:manager');

    });
    //printPdf & ExportExcel
    Route::get('/items/export/pdf', [ItemController::class, 'exportPDF'])->name('items.export.pdf');
    Route::get('/items/export/excel', [ItemController::class, 'exportExcel'])->name('items.export.excel');

    //Testing total modal
    Route::get('/manager/finance/primaryModal', [ModalController::class, 'primaryModal'])->name('manager.finance.modal.primaryModal');
});

//Fungsi get data ajaxx
Route::get('/items-data', [ItemController::class, 'getItems']);

require __DIR__ . '/auth.php';
