<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    InboundController,
    ProductController,
    CategoryController,
    CustomerController,
    DashboardController,
    SupplierController,
    SalesDetailController,
    ReturDetailController,
    InboundDetailController,
    SalesController,
    ReturController,
    ReportController,
    UserController,
    SettingController
};

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

Route::get('/', fn () => redirect()->route('login'));

Route::group(['middleware' => 'auth'], function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'can:master'], function () {
        Route::get('/category/data', [CategoryController::class, 'data'])->name('category.data');
        Route::resource('/category', CategoryController::class);

        Route::get('/product/data', [ProductController::class, 'data'])->name('product.data');
        Route::post('/product/delete-selected', [ProductController::class, 'deleteSelected'])->name('product.delete_selected');
        Route::post('/product/print-barcode', [ProductController::class, 'printBarcode'])->name('product.print_barcode');
        Route::resource('/product', ProductController::class);

        Route::get('/customer/data', [CustomerController::class, 'data'])->name('customer.data');
        Route::resource('/customer', CustomerController::class);

        Route::get('/supplier/data', [SupplierController::class, 'data'])->name('supplier.data');
        Route::resource('/supplier', SupplierController::class);

        Route::get('/report', [ReportController::class, 'index'])->name('report.index');
        Route::get('/report/data/{start}/{end}', [ReportController::class, 'data'])->name('report.data');
        Route::get('/report/pdf/{start}/{end}', [ReportController::class, 'exportPDF'])->name('report.export_pdf');
    });


    Route::get('/inbound/data', [InboundController::class, 'data'])->name('inbound.data');
    Route::get('/inbound/{id}/create', [InboundController::class, 'create'])->name('inbound.create');
    Route::resource('/inbound', InboundController::class)
        ->except('create');

    Route::get('/inbound_detail/{id}/data', [InboundDetailController::class, 'data'])->name('inbound_detail.data');
    Route::get('/inbound_detail/loadform/{discount}/{total_price}', [InboundDetailController::class, 'loadForm'])->name('inbound_detail.loadform');
    Route::resource('/inbound_detail', InboundDetailController::class)
        ->except('create', 'show', 'edit');

    Route::get('/sales/data', [SalesController::class, 'data'])->name('sales.data');
    Route::get('/sales/save', [SalesController::class, 'save'])->name('sales.save');
    Route::get('/sales/note', [SalesController::class, 'note'])->name('sales.note');
    Route::get('/sales/{id}/create', [SalesController::class, 'create'])->name('sales.create');
    Route::resource('/sales', SalesController::class)
        ->except('create');

    Route::get('/sales_detail/{id}/data', [SalesDetailController::class, 'data'])->name('sales_detail.data');
    Route::get('/sales_detail/loadform/{discount}/{total_price}', [SalesDetailController::class, 'loadForm'])->name('sales_detail.loadform');
    Route::resource('/sales_detail', SalesDetailController::class)
        ->except('create', 'show', 'edit');

    Route::get('/retur/data', [ReturController::class, 'data'])->name('retur.data');
    Route::get('/retur/save', [ReturController::class, 'save'])->name('retur.save');
    Route::get('/retur/note', [ReturController::class, 'note'])->name('retur.note');
    Route::get('/retur/{id}/create', [ReturController::class, 'create'])->name('retur.create');
    Route::resource('/retur', ReturController::class)
        ->except('create');

    Route::get('/retur_detail/{id}/data', [ReturDetailController::class, 'data'])->name('retur_detail.data');
    Route::get('/retur_detail/loadform/{discount}/{total_price}', [ReturDetailController::class, 'loadForm'])->name('retur_detail.loadform');
    Route::resource('/retur_detail', ReturDetailController::class)
        ->except('create', 'show', 'edit');

    Route::group(['middleware' => 'can:setting'], function () {
        Route::get('/user/data', [UserController::class, 'data'])->name('user.data');
        Route::resource('/user', UserController::class);

        Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/setting/first', [SettingController::class, 'show'])->name('setting.show');
        Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');
    });


    Route::get('/profile', [UserController::class, 'profile'])->name('user.profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('user.update_profile');
});
