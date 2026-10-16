<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');

    Route::post('/', [AttendanceController::class, 'store'])->name('attendance.store');

    Route::get('/month', [AttendanceController::class, 'month'])->name('attendance.month');

    Route::get('/user/password', [UserController::class, 'password'])->name('user.password');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('user/create', [UserController::class, 'store'])->name('user.store');

    Route::get('user/create-complete', [UserController::class, 'createComplete'])->name('user.create_complete');

    Route::get('/user', [UserController::class, 'index'])->name('user.index');

    Route::get('/user/{user}/record/month', [AttendanceController::class, 'adminMonth'])->name('attendance.admin_month');
    Route::get('/user/{user_id}/record/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::post('/user/{user_id}/record/{attendance}/edit', [AttendanceController::class, 'update'])->name('attendance.update');

    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/{user}/edit', [UserController::class, 'update'])->name('user.update');

    Route::post('/user/{user}/change-is-enabled', [UserController::class, 'changeIsEnabled'])->name('user.change_is_enabled');
});
