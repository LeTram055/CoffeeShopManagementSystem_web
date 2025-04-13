<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffCounter\HomeController;
use App\Http\Controllers\StaffCounter\OrderController;
use App\Http\Controllers\StaffCounter\ConfirmOrderController;
use App\Http\Controllers\StaffCounter\ReportController;

//Home
Route::middleware(['auth', 'role:staff_counter'])->group(function () {
Route::get('staff_counter/home',
    [HomeController::class, 'index'])
->name('staff_counter.home.index');

Route::get('staff_counter/order',
[OrderController::class, 'index'])
->name('staff_counter.order.index');

Route::post('staff_counter/order/save-customer',
[OrderController::class, 'saveCustomer'])
->name('staff_counter.order.save_customer');

Route::post('staff_counter/order/save',
[OrderController::class, 'save'])
->name('staff_counter.order.save');

//Confirm Order
Route::get('staff_counter/confirmoder',
[ConfirmOrderController::class, 'index'])
->name('staff_counter.confirmorder.index');

Route::get('staff_counter/confirmorder/{orderId}',
[ConfirmOrderController::class, 'show'])
->whereNumber('orderId')
->name('staff_counter.confirmorder.show');

Route::post('staff_counter/confirmorder/update-takeaway/{orderId}',
[ConfirmOrderController::class, 'updateTakeaway'])
->whereNumber('orderId')
->name('staff_counter.confirmorder.update_takeaway');

Route::get( 'staff_counter/confirmorder/menuitem',
[ConfirmOrderController::class, 'showMenuItem'])
->name('staff_counter.confirmorder.show_menu_item');

Route::post('staff_counter/confirmorder/cancel/{orderId}',
[ConfirmOrderController::class, 'cancelOrder'])
->whereNumber('orderId')
->name('staff_counter.confirmorder.cancel');

Route::get('staff_counter/confirmorder/eligible-promotions/{order_id}', 
[ConfirmOrderController::class, 'eligiblePromotions'])
->whereNumber('order_id')
->name('staff_counter.confirmorder.eligible_promotions');

Route::post('staff_counter/confirmorder/payment_takeaway',
 [ConfirmOrderController::class, 'paymentTakeaway'])
->name('staff_counter.confirmorder.payment_takeaway');

Route::get('/staff_counter/confirmorder/print-provisional-invoice/{order_id}', 
[ConfirmOrderController::class, 'printProvisionalInvoice'])
    ->name('staff_counter.confirmorder.printProvisionalInvoice');

Route::get('/staff_counter/confirmorder/print-invoice/{order_id}', 
[ConfirmOrderController::class, 'printInvoice'])
    ->name('staff_counter.confirmorder.printInvoice');

Route::post('/staff_counter/confirmorder/mark-paid/{order_id}', [\App\Http\Controllers\StaffCounter\ConfirmOrderController::class, 'markPaid'])
    ->name('staff_counter.confirmorder.markPaid');

Route::get('/staff_counter/reports/index', 
[ReportController::class, 'index'])->name('staff_counter.reports.index');

Route::get('/staff_counter/reports/get-total',
[ReportController::class, 'getTotal'])
->name('staff_counter.reports.getTotal');

});