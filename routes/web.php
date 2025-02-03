<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SetupController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SystemSettingsController;
use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\AccHeadController;
use Illuminate\Support\Facades\Route;

Route::get('/',[AuthController::class,'index'])->name('login');
Route::post('authenticate',[AuthController::class,'authenticate']);

Route::get('step/one', [SetupController::class, 'admin']);
Route::post('step/one/store', [SetupController::class, 'admin_store']);
Route::get('step/two', [SetupController::class, 'step_two']);
Route::post('step/two/store', [SetupController::class, 'step_two_store']);

Route::middleware(['auth'])->group(function(){
    Route::get('logout',[AuthController::class,'logout']);
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('item/reorder',[DashboardController::class,'low_items']);
    #user information
    Route::get('user',[UserController::class,'index']);
    Route::post('user/store',[UserController::class,'store']);
    Route::post('user/update',[UserController::class,'update']);
    #account settings
    Route::get('account/settings',[AccountSettingsController::class,'index']);
    Route::post('account/settings/update',[AccountSettingsController::class,'account_update']);
    Route::post('account/settings/change/password',[AccountSettingsController::class,'change_password']);
    #categories
    Route::get('categories',[CategoriesController::class,'index']);
    Route::post('categories/store',[CategoriesController::class,'store']);
    Route::post('categories/update',[CategoriesController::class,'update']);
    #company
    Route::get('companies',[CompaniesController::class,'index']);
    Route::post('companies/store',[CompaniesController::class,'store']);
    Route::post('companies/update',[CompaniesController::class,'update']);
    Route::get('company/users',[CompaniesController::class,'user']);
    Route::post('company/user/store',[CompaniesController::class,'user_store']);
    Route::post('company/user/update',[CompaniesController::class,'user_update']);
    Route::post('company/user/delete',[CompaniesController::class,'user_delete']);
    Route::get('company/purchase',[CompaniesController::class,'purchase']);
    Route::post('company/purchase/store',[CompaniesController::class,'purchase_store']);
    Route::post('company/purchase/due/update',[CompaniesController::class,'purchase_due_update']);
    #items
    Route::get('item',[ItemController::class,'index']);
    Route::get('item/add',[ItemController::class,'add']);
    Route::post('item/store',[ItemController::class,'store']);
    Route::post('item/update/sell/price',[ItemController::class,'update_sell_price']);
    Route::post('item/update/quantity',[ItemController::class,'update_quantity']);
    Route::get('item/edit',[ItemController::class,'edit']);
    Route::post('item/update',[ItemController::class,'update']);
    #customer
    Route::get('customer',[CustomerController::class,'index']);
    Route::post('customer/store',[CustomerController::class,'store']);
    Route::post('customer/update',[CustomerController::class,'update']);
    Route::get('customer/orders',[CustomerController::class,'customer_order']);
    Route::get('customer/due/payments',[CustomerController::class,'customer_due_paid']);
    Route::post('customer/due/payment/update',[CustomerController::class,'payment_update']);
    #expense
    Route::get('expense',[ExpenseController::class,'index']);
    Route::post('expense/store',[ExpenseController::class,'store']);
    Route::post('expense/update',[ExpenseController::class,'update']);
    #account head
    Route::get('achead',[AccHeadController::class,'index']);
    Route::post('achead/store',[AccHeadController::class,'store']);
    Route::post('achead/update',[AccHeadController::class,'update']);
    #pos
    Route::get('pos',[SalesController::class,'index']);
    Route::post('view/pos',[SalesController::class,'temp_order']);
    Route::post('change/quantity',[SalesController::class,'change_quantity']);
    Route::post('delete/item',[SalesController::class,'delete_item']);
    Route::post('pos/complete/order',[SalesController::class,'complete_order']);
    Route::get('print/pos/invoice',[SalesController::class,'print_invoice']);
    #reports
    Route::get('report',[ReportController::class,'index']);
    Route::get('report/sales',[ReportController::class,'sales_report']);
    Route::get('report/sales/print',[ReportController::class,'print_sales_report']);
    Route::get('report/expenses/',[ReportController::class,'expense_report']);
    Route::get('report/expense/print',[ReportController::class,'expense_report_print']);
    #system settings
    Route::get('system/settings',[SystemSettingsController::class,'index']);
    Route::post('system/settings/update',[SystemSettingsController::class,'update']);
});



