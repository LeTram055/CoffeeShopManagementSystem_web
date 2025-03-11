<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffServe\OrderController;

Route::get('staff-serve/tables', 
[OrderController::class, 'getTable']);

Route::get('staff-serve/customers', 
[OrderController::class, 'getCustomers']);

Route::post('staff-serve/customers', 
[OrderController::class, 'addCustomer']);

Route::get('staff-serve/menu', 
[OrderController::class, 'getMenu']);

Route::post('staff-serve/orders/create',
[OrderController::class, 'createOrder']);

Route::get('staff-serve/orders/{tableId}',
[OrderController::class, 'getOrderByTableId'])
->whereNumber('tableId');

Route::post('staff-serve/orders/update',
[OrderController::class, 'updateOrder'])
->whereNumber('orderId');

Route::post('staff-serve/orders/cancel/{orderId}',
[OrderController::class, 'cancelOrder']);

Route::get('staff-serve/promotions/{orderId}',
[OrderController::class, 'eligiblePromotions']);

Route::post('staff-serve/payment/create',
[OrderController::class, 'createPayment']);