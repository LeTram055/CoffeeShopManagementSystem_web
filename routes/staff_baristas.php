<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffBaristas\MenuController;
use App\Http\Controllers\StaffBaristas\OrderController;
use App\Http\Controllers\StaffBaristas\IngredientController;

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

Route::get('/staff_baristas/menu/index', 
[MenuController::class, 'index'])
->name('staff_baristas.menu.index');

Route::post('/staff_baristas/menu/toggle-availability/{id}', 
[MenuController::class, 'toggleAvailability'])
->whereNumber('id')
->name('staff_baristas.menu.toggle-availability');

Route::get('/staff_baristas/ingredient/index', 
[IngredientController::class, 'index'])
->name('staff_baristas.ingredient.index');

Route::post('/staff_baristas/ingredient/update/{id}', 
[IngredientController::class, 'update'])
->name('staff_baristas.ingredient.update');