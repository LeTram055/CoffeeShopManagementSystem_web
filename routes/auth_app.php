<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthAppController;

Route::post('auth-app/login',
[AuthAppController::class, 'login']);

Route::post('auth-app/change-password',
[AuthAppController::class, 'changePassword']);

// Route::get('auth-app/profile', 
// [AuthAppController::class, 'getProfile']);

Route::get('auth-app/work-schedules', 
[AuthAppController::class, 'getWorkSchedules']);

Route::get('auth-app/bonuses-penalties', 
[AuthAppController::class, 'getBonusesPenalties']);

Route::get('auth-app/salaries', 
[AuthAppController::class, 'getSalaries']);