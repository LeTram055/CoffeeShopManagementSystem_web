<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthAppController;

Route::post('auth-app/login',
[AuthAppController::class, 'login']);

Route::post('auth-app/change-password',
[AuthAppController::class, 'changePassword']);