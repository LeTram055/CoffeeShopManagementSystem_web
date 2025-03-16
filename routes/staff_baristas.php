<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffBaristas\MenuController;
use App\Http\Controllers\StaffBaristas\IngredientController;
use App\Http\Controllers\StaffBaristas\OrderController;

// Route::get('staff-barista/menu-items', 
// [MenuController::class, 'index']);

// Route::get('staff-barista/categories',
// [MenuController::class, 'getCategory']);

// Route::put('staff-barista/menu-items/{id}/toggle', 
// [MenuController::class, 'toggleAvailability']);

// Route::get('staff-barista/menu-items/{id}/ingredients', 
// [MenuController::class, 'getIngredients']);

// Route::get('staff-barista/ingredients', 
// [IngredientController::class, 'index']);

// Route::put('staff-barista/ingredients/{id}/update-quantity',
//  [IngredientController::class, 'updateQuantity']);

Route::get('staff_baristas/order/index',
[OrderController::class, 'index'])
->name('staff_baristas.order.index');

Route::get('staff_baristas/order/detail/{id}', 
[OrderController::class, 'showDetail'])
->name('staff_baristas.order.detail');


Route::post('staff_baristas/order/complete/{id}', 
[OrderController::class, 'completeOrder'])
->whereNumber('id')
->name('staff_baristas.order.complete');