<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskListController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::middleware(['auth'])->group(function() {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('admin.dashboard');
        Route::get('profile','profile')->name('admin.profile');
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category', 'index')->name('admin.category');
        Route::post('category/store', 'store')->name('admin.category.store');
        Route::delete('category/{id}/delete','delete')->name('admin.category.delete');
    });

    Route::controller(TaskListController::class)->group(function() {
        Route::get('task-list','index')->name('admin.taskList');
        Route::post('task-list/store','store')->name('admin.taskList.store');
        Route::delete('task-list/{id}/delete','delete')->name('admin.taskList.delete');
    });

    Route::controller(TaskController::class)->group(function() {
        Route::get('task','index')->name('admin.task');
        Route::post('task/store','store')->name('admin.task.store');
        Route::get('task/{id}/edit','edit')->name('admin.edit');
        Route::put('task/{id}/update','update')->name('task.update');
        Route::delete('task/{id}/delete','delete')->name('admin.task.delete');
    });

});





