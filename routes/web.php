<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

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