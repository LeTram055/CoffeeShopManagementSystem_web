<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffBarista\MenuController;
use App\Http\Controllers\StaffBarista\IngredientController;
use App\Http\Controllers\StaffBarista\OrderController;

Route::get('staff-barista/menu-items', 
[MenuController::class, 'index']);

Route::get('staff-barista/categories',
[MenuController::class, 'getCategory']);

Route::put('staff-barista/menu-items/{id}/toggle', 
[MenuController::class, 'toggleAvailability']);

Route::get('staff-barista/menu-items/{id}/ingredients', 
[MenuController::class, 'getIngredients']);

Route::get('staff-barista/ingredients', 
[IngredientController::class, 'index']);

Route::put('staff-barista/ingredients/{id}/update-quantity',
 [IngredientController::class, 'updateQuantity']);

Route::get('staff-barista/orders',
[OrderController::class, 'index']);

Route::post('staff-barista/orders/{id}/complete', 
[OrderController::class, 'completeOrder']);