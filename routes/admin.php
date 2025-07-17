<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskListController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::middleware([])->group(function() {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('admin.dashboard');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category', 'index')->name('admin.category');
        Route::post('category/store', 'store')->name('admin.category.store');
    });

    Route::controller(TaskListController::class)->group(function() {
        Route::get('task-list','index')->name('admin.taskList');
        Route::post('task-list/store','store')->name('admin.taskList.store');
    });
});





