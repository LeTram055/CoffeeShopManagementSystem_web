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
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\BonusPenaltyController;
use App\Http\Controllers\Admin\SalaryController;
use App\Http\Controllers\Admin\WorkScheduleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Redis;

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
->name('logout')
->middleware('auth');

Route::get('/password/change',
 [AuthController::class, 'showChangePasswordForm'])
->name('password.change')
->middleware('auth');

Route::post('/password/change', 
[AuthController::class, 'updatePassword'])
->name('password.update')
->middleware('auth');

Route::get('profile', 
[AuthController::class, 'profile'])
->name('profile')
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

Route::get('/admin/payment/print-invoice/{order_id}', 
[PaymentController::class, 'printInvoice'])
->whereNumber('order_id')
->name('admin.payment.printInvoice');

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

//Report

Route::get('admin/reports/revenue-summary-page', 
[ReportController::class, 'revenueSummaryPage'])
->name('admin.reports.revenueSummaryPage');

Route::get('admin/reports/revenue-summary', 
[ReportController::class, 'revenueSummary'])
->name('admin.reports.revenueSummary');

Route::get('admin/reports/revenue-by-product-page', 
[ReportController::class, 'revenueByProductPage'])
->name('admin.reports.revenueByProductPage');

Route::get('admin/reports/revenue-by-product', 
[ReportController::class, 'revenueByProduct'])
->name('admin.reports.revenueByProduct');

Route::get('admin/reports/revenue-by-hour-page', 
[ReportController::class, 'revenueByHourPage'])
->name('admin.reports.revenueByHourPage');

Route::get('admin/reports/revenue-by-hour', 
[ReportController::class, 'revenueByHour'])
->name('admin.reports.revenueByHour');

Route::get('/admin/reports/revenue-by-order-type-page',
 [ReportController::class, 'revenueByOrderTypePage'])
 ->name('admin.reports.revenueByOrderTypePage');

Route::get('/admin/reports/revenue-by-order-type', 
[ReportController::class, 'revenueByOrderType'])
->name('admin.reports.revenueByOrderType');




Route::get('admin/reports/net-profit-page', 
[ReportController::class, 'netProfitPage'])
->name('admin.reports.netProfitPage');

Route::get('admin/reports/net-profit', 
[ReportController::class, 'netProfit'])
->name('admin.reports.netProfit');

Route::get('/admin/reports/best-selling-page', 
[ReportController::class, 'bestSellingProductsPage'])
->name('admin.reports.bestSellingPage');

Route::get('/admin/reports/best-selling', 
[ReportController::class, 'bestSellingProducts'])
->name('admin.reports.bestSelling');


//Shift
Route::get('admin/shift',
[ShiftController::class, 'index'])
->name('admin.shift.index');

Route::get('admin/shift/export-excel',
[ShiftController::class, 'exportExcel'])
->name('admin.shift.exportExcel');

Route::post('admin/shift/delete',
[ShiftController::class, 'destroy'])
->name('admin.shift.delete');

Route::get('admin/shift/create',
[ShiftController::class, 'create'])
->name('admin.shift.create');

Route::post('admin/shift/save',
[ShiftController::class, 'save'])
->name('admin.shift.save');

Route::get('admin/shift/edit',
[ShiftController::class, 'edit'])
->name('admin.shift.edit');

Route::post('admin/shift/update',
[ShiftController::class, 'update'])
->name('admin.shift.update');

//Work Schedules
Route::get('admin/workschedule',
[WorkScheduleController::class, 'index'])
->name('admin.workschedule.index');

Route::get('admin/workschedule/export-excel',
[WorkScheduleController::class, 'exportExcel'])
->name('admin.workschedule.exportExcel');

Route::post('admin/workschedule/delete',
[WorkScheduleController::class, 'destroy'])
->name('admin.workschedule.delete');

Route::get('admin/workschedule/create',
[WorkScheduleController::class, 'create'])
->name('admin.workschedule.create');

Route::post('admin/workschedule/save',
[WorkScheduleController::class, 'save'])
->name('admin.workschedule.save');

Route::get('admin/workschedule/edit',
[WorkScheduleController::class, 'edit'])
->name('admin.workschedule.edit');

Route::post('admin/workschedule/update',
[WorkScheduleController::class, 'update'])
->name('admin.workschedule.update');
});

Route::get('/admin/workschedule/schedule-view', 
[WorkScheduleController::class, 'scheduleView'])
->name('admin.workschedule.scheduleView');

//Bonus Penalty
Route::get('admin/bonuspenalty',
[BonusPenaltyController::class, 'index'])
->name('admin.bonuspenalty.index');

Route::get('admin/bonuspenalty/export-excel',
[BonusPenaltyController::class, 'exportExcel'])
->name('admin.bonuspenalty.exportExcel');

Route::post('admin/bonuspenalty/delete',
[BonusPenaltyController::class, 'destroy'])
->name('admin.bonuspenalty.delete');

Route::get('admin/bonuspenalty/create',
[BonusPenaltyController::class, 'create'])
->name('admin.bonuspenalty.create');

Route::post('admin/bonuspenalty/save',
[BonusPenaltyController::class, 'save'])
->name('admin.bonuspenalty.save');

Route::get('admin/bonuspenalty/edit',
[BonusPenaltyController::class, 'edit'])
->name('admin.bonuspenalty.edit');

Route::post('admin/bonuspenalty/update',
[BonusPenaltyController::class, 'update'])
->name('admin.bonuspenalty.update');

//Salary
Route::get('admin/salary',
[SalaryController::class, 'index'])
->name('admin.salary.index');

Route::get('admin/salary/export-excel',
[SalaryController::class, 'exportExcel'])
->name('admin.salary.exportExcel');

Route::post('admin/salary/delete',
[SalaryController::class, 'destroy'])
->name('admin.salary.delete');

Route::get('admin/salary/create',
[SalaryController::class, 'create'])
->name('admin.salary.create');

Route::post('admin/salary/save',
[SalaryController::class, 'save'])
->name('admin.salary.save');

Route::get('admin/salary/edit',
[SalaryController::class, 'edit'])
->name('admin.salary.edit');

Route::post('admin/salary/update',
[SalaryController::class, 'update'])
->name('admin.salary.update');

Route::get('/admin/salary/{salary_id}',
 [SalaryController::class, 'showDetails'])
->name('admin.salary.details');

Route::get('/admin/salary/export-pdf/{salaryId}',
 [SalaryController::class, 'exportPdf'])
 ->whereNumber('salaryId')
->name('admin.salary.exportPdf');


//Setting
Route::get('/admin/settings/edit', 
[SettingController::class, 'edit'])
->name('admin.settings.edit');

Route::post('/admin/settings/update', 
[SettingController::class, 'update'])
->name('admin.settings.update');

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

//-----------------------------------------------------------------------------//
//Message
Route::get('/send-message', 
[ChatController::class, 'sendMessage']);

Route::get('/test-redis', function () {
    Redis::publish('chat', json_encode(['message' => 'Hello từ Laravel!']));
    return 'Message sent to Redis!';
});

require base_path('routes/channels.php');