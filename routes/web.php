<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\IngredientLogController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\TableController;

use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'showLoginForm']);
Route::get('/admin', [AuthController::class, 'showLoginForm']);
Route::get('/staff_counter', [AuthController::class, 'showLoginForm']);

//Auth
Route::get('login', 
[AuthController::class, 'showLoginForm'])
->name('login');
Route::post('login', 
[AuthController::class, 'login'])
->name('login.post');
Route::post('logout',
 [AuthController::class, 'logout'])
->name('logout');
Route::get('/password/change',
 [AuthController::class, 'showChangePasswordForm'])
->name('password.change')
->middleware('auth');
Route::post('/password/change', 
[AuthController::class, 'updatePassword'])
->name('password.update')
->middleware('auth');


//Amin
Route::middleware(['auth', 'role:admin'])->group(function () {
//Home
Route::get('admin/home', 
[HomeController::class, 'index'])
->name('admin.home.index');

//Category
Route::get('admin/category',
[CategoryController::class, 'index'])
->name('admin.category.index');

Route::get('admin/category/export-excel',
[CategoryController::class, 'exportExcel'])
->name('admin.category.exportExcel');

Route::post('admin/category/delete',
[CategoryController::class, 'destroy'])
->name('admin.category.delete');

Route::get('admin/category/create',
[CategoryController::class, 'create'])
->name('admin.category.create');

Route::post('admin/category/save',
[CategoryController::class, 'save'])
->name('admin.category.save');

Route::get('admin/category/edit',
[CategoryController::class, 'edit'])
->name('admin.category.edit');

Route::post('admin/category/update',
[CategoryController::class, 'update'])
->name('admin.category.update');

//Ingredient
Route::get('admin/ingredient',
[IngredientController::class, 'index'])
->name('admin.ingredient.index');

Route::get('admin/ingredient/export-excel',
[IngredientController::class, 'exportExcel'])
->name('admin.ingredient.exportExcel');

Route::post('admin/ingredient/delete',
[IngredientController::class, 'destroy'])
->name('admin.ingredient.delete');

Route::get('admin/ingredient/create',
[IngredientController::class, 'create'])
->name('admin.ingredient.create');

Route::post('admin/ingredient/save',
[IngredientController::class, 'save'])
->name('admin.ingredient.save');

Route::get('admin/ingredient/edit',
[IngredientController::class, 'edit'])
->name('admin.ingredient.edit');

Route::post('admin/ingredient/update',
[IngredientController::class, 'update'])
->name('admin.ingredient.update');

//Ingredient Log
Route::get('admin/ingredientlog',
[IngredientLogController::class, 'index'])
->name('admin.ingredientlog.index');

Route::get('admin/ingredientlog/export-excel',
[IngredientLogController::class, 'exportExcel'])
->name('admin.ingredientlog.exportExcel');

Route::post('admin/ingredientlog/delete',
[IngredientLogController::class, 'destroy'])
->name('admin.ingredientlog.delete');

//Promotion
Route::get('admin/promotion',
[PromotionController::class, 'index'])
->name('admin.promotion.index');

Route::get('admin/promotion/export-excel',
[PromotionController::class, 'exportExcel'])
->name('admin.promotion.exportExcel');

Route::post('admin/promotion/delete',
[PromotionController::class, 'destroy'])
->name('admin.promotion.delete');

Route::get('admin/promotion/create',
[PromotionController::class, 'create'])
->name('admin.promotion.create');

Route::post('admin/promotion/save',
[PromotionController::class, 'save'])
->name('admin.promotion.save');

Route::get('admin/promotion/edit',
[PromotionController::class, 'edit'])
->name('admin.promotion.edit');

Route::post('admin/promotion/update',
[PromotionController::class, 'update'])
->name('admin.promotion.update');

//Customer
Route::get('admin/customer',
[CustomerController::class, 'index'])
->name('admin.customer.index');

Route::get('admin/customer/export-excel',
[CustomerController::class, 'exportExcel'])
->name('admin.customer.exportExcel');

// Route::post('admin/customer/delete',
// [CustomerController::class, 'destroy'])
// ->name('admin.customer.delete');

Route::get('admin/customer/create',
[CustomerController::class, 'create'])
->name('admin.customer.create');

Route::post('admin/customer/save',
[CustomerController::class, 'save'])
->name('admin.customer.save');

Route::get('admin/customer/edit',
[CustomerController::class, 'edit'])
->name('admin.customer.edit');

Route::post('admin/customer/update',
[CustomerController::class, 'update'])
->name('admin.customer.update');


//Employee
Route::get('admin/employee',
[EmployeeController::class, 'index'])
->name('admin.employee.index');

Route::get('admin/employee/export-excel',
[EmployeeController::class, 'exportExcel'])
->name('admin.employee.exportExcel');

Route::post('admin/employee/delete',
[EmployeeController::class, 'destroy'])
->name('admin.employee.delete');

Route::get('admin/employee/create',
[EmployeeController::class, 'create'])
->name('admin.employee.create');

Route::post('admin/employee/save',
[EmployeeController::class, 'save'])
->name('admin.employee.save');

Route::get('admin/employee/edit',
[EmployeeController::class, 'edit'])
->name('admin.employee.edit');

Route::post('admin/employee/update',
[EmployeeController::class, 'update'])
->name('admin.employee.update');

//Menu Item
Route::get('admin/menuitem',
[MenuItemController::class, 'index'])
->name('admin.menuitem.index');

Route::get('/admin/menuitem/{id}',
 [MenuItemController::class, 'show'])
 ->whereNumber('id')
 ->name('admin.menuitem.show');

Route::get('admin/menuitem/export-excel', 
[MenuItemController::class, 'exportExcel'])
->name('admin.menuitem.exportExcel');

Route::post('admin/menuitem/delete',
[MenuItemController::class, 'destroy'])
->name('admin.menuitem.delete');

Route::get('admin/menuitem/create',
[MenuItemController::class, 'create'])
->name('admin.menuitem.create');

Route::post('admin/menuitem/save',
[MenuItemController::class, 'save'])
->name('admin.menuitem.save');

Route::get('admin/menuitem/edit',
[MenuItemController::class, 'edit'])
->name('admin.menuitem.edit');

Route::post('admin/menuitem/update',
[MenuItemController::class, 'update'])
->name('admin.menuitem.update');

//Payment
Route::get('admin/payment/index',
[PaymentController::class, 'index'])
->name('admin.payment.index');

Route::get('admin/payment/{id}', 
[PaymentController::class, 'show'])
->whereNumber('id')
->name('admin.payment.show');


Route::get('admin/payment/export-excel',
[PaymentController::class, 'exportExcel'])
->name('admin.payment.exportExcel');

Route::post('admin/payment/delete',
[PaymentController::class, 'destroy'])
->name('admin.payment.delete');

//Table
Route::get('admin/table/index',
[TableController::class, 'index'])
->name('admin.table.index');

Route::get('admin/table/export-excel',
[TableController::class, 'exportExcel'])
->name('admin.table.exportExcel');

Route::post('admin/table/delete',
[TableController::class, 'destroy'])
->name('admin.table.delete');

Route::get('admin/table/create',
[TableController::class, 'create'])
->name('admin.table.create');

Route::post('admin/table/save',
[TableController::class, 'save'])
->name('admin.table.save');

Route::get('admin/table/edit',
[TableController::class, 'edit'])
->name('admin.table.edit');

Route::post('admin/table/update',
[TableController::class, 'update'])
->name('admin.table.update');
});
//-----------------------------------------------------------------------------//
//Staff Counter
require base_path('routes/staff_counter.php');

//-----------------------------------------------------------------------------//
//Staff Barista
require base_path('routes/staff_barista.php');

//-----------------------------------------------------------------------------//
//auth app
require base_path('routes/auth_app.php');

//-----------------------------------------------------------------------------//
//Staff Serve
require base_path('routes/staff_serve.php');

//-----------------------------------------------------------------------------//
//Staff Baristas
require base_path('routes/staff_baristas.php');