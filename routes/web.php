<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\
{
    AuthController,
};

use App\Http\Controllers\Maintain\{
    FiscalYearController,
    SettingsController
};

use App\Http\Controllers\Accounts\{
    VoucherController,
    ChartOfAccountsController
};

use App\Http\Controllers\Reports\{
    ReportsController
};

use App\Http\Controllers\Items\
{
    ItemCategoryController,
    ItemController
};

use App\Http\Controllers\Sales\
{
    CustomerController,
    InvoiceController
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

//Public Main Route
Route::get('/', function(){
    try {
        DB::connection()->getPDO();
        //dump('Database connected: ' . \DB::connection()->getDatabaseName());
        //return view('auth.login');
        return redirect()->route('login');
    }
    catch (Exception $e) {
        //dump('Database connected: ' . 'None');
        return abort(503);
    }
});

//Auth Routes
Route::controller(AuthController::class)->group(function (){
    Route::get('/login', 'index')->name('login');
    Route::post('/login/auth', 'loginAuth')->name('login.auth');
    Route::get('/logout', 'logout')->name('logout');
});

Route::group(['middleware' => 'auth'], function() {

    //Fiscal Year Routes
    Route::resource('fiscalYear', FiscalYearController::class);

    //Settings
    Route::controller(SettingsController::class)->group(function (){
        Route::get('/maintain/settings', 'index')->name('settings');
        Route::post('settings/storeCashPaymentAccount', 'storeCashPaymentAccount')->name('storeCashPaymentAccount');
        //Route::post('settings/storeBankPaymentAccount', 'storeBankPaymentAccount')->name('storeBankPaymentAccount');
    });

    //Item Category Routes
    Route::controller(ItemCategoryController::class)->group(function(){
        Route::get('/items/itemsCategory', 'index')->name('items/itemsCategory');
        Route::post('/items/itemsCategory/addParentCategory', 'storeParentCategory')->name('items/itemsCategory/addParentCategory');
        Route::post('/items/itemsCategory/addItemCategory', 'storeItemCategory')->name('items/itemsCategory/addItemCategory');
        Route::put('/items/itemsCategory/{id}', 'updateItemCategory')->name('items/itemsCategory');
    });

    //Item Routes
    Route::controller(ItemController::class)->group(function (){
        Route::get('items/getSubCategories/{id}', 'getSubCategories')->name('items/getSubCategories');
        Route::get('item/edit/{id}', 'edit')->name('item/edit');
        Route::put('item/update/{id}', 'update')->name('item/update');
        Route::get('item/show/{id}', 'show')->name('item/show');
    });

    Route::resource('items', ItemController::class);

    //Customer Routes
    Route::resource('customer', CustomerController::class);

    //Invoice Routes
    Route::resource('invoice', InvoiceController::class);

    //Chart of Accounts
    Route::controller(ChartOfAccountsController::class)->group(function (){
        Route::get('accounts/chartOfAccounts',  'index')->name('accounts/chartOfAccounts');
        Route::post('/addParentAccount', 'addParentAccount')->name('addParentAccount');
        Route::get('getParentAccount/{id}', 'getParentAccounts')->name('getParentAccounts');
        Route::post('accounts/store','store')->name('accounts/store');
        Route::get('getControllingAccount/{id}', 'getControllingAccount')->name('getControllingAccount');
        Route::post('updateAccount/{id}', 'updateAccount')->name('updateAccount');
    });

    //Vouchers
    Route::controller(VoucherController::class)->group(function (){
        Route::get('/accounts/dashboard', 'index')->name('accountsDashboard');
        Route::get('accounts/voucher/{voucher}', 'createVoucher')->name('createVoucher');
        Route::get('getAccounts/{id}', 'getAccounts')->name('getAccounts');

        //Route::get('accounts/drVoucher', 'createDrVoucher')->name('createDrVoucher');
        Route::post('accounts/drVoucher/store', 'storeDrVoucher')->name('accounts/drVoucher/store');
        Route::post('accounts/crVoucher/store', 'storeCrVoucher')->name('accounts/crVoucher/store');
        Route::post('accounts/jVoucher/store', 'storeJVoucher')->name('accounts/jVoucher/store');

        Route::get('getTrnDetails/{id}', 'showTrnDetails')->name('getTrnDetails');
        Route::put('updateTrnStatus/{id}', 'updateTrnStatus')->name('updateTrnStatus');
        Route::post('updateBulkTrnStatus', 'updateBulkTrnStatus')->name('updateBulkTrnStatus');
    });

    //Reporting
    Route::controller(ReportsController::class)->group(function (){
        Route::get('/accounts/showLedgerReport', 'index')->name('accounts/showLedgerReport');
        Route::post('/accounts/showLedgerReport', 'ledgerReport')->name('accounts/showLedgerReport');
        Route::post('/accounts/getVoucherTrns', 'getVoucherTrns')->name('getVoucherTrns');

        Route::get('/accounts/report/trialBalance', 'trialBalanceReport')->name('accounts/report/trialBalance');

        Route::get('/accounts/report/incomeStatement', 'viewIncomeStatementReport')->name('accounts/report/incomeStatement');
        Route::post('/accounts/report/incomeStatement', 'incomeStatementReport')->name('accounts/report/incomeStatement');

        Route::get('/accounts/report/balanceSheet', 'viewBalanceSheetReport')->name('accounts/report/balanceSheet');
        Route::post('/accounts/report/balanceSheet', 'balanceSheetReport')->name('accounts/report/balanceSheet');
    });
});

