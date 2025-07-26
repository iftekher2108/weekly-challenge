<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::middleware(['auth'])->group(function() {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('admin.dashboard');
        Route::get('profile','profile')->name('admin.profile');
    });

    Route::put('profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');

    Route::controller(CategoryController::class)->group(function () {
        Route::get('/category', 'index')->name('admin.category');
        Route::post('category/store', 'store')->name('admin.category.store');
        Route::get('category/{id}/edit','edit')->name('admin.category.edit');
        Route::put('category/{id}/update','update')->name('admin.category.update');
        Route::delete('category/{id}/delete','delete')->name('admin.category.delete');
    });

    Route::controller(TaskController::class)->group(function() {
        Route::get('task','index')->name('admin.task');
        Route::post('task/store','store')->name('admin.task.store');
        Route::get('task/{id}/edit','edit')->name('admin.task.edit');
        Route::put('task/{id}/update','update')->name('admin.task.update');
        Route::put('task/{id}/progress','taskProgress')->name('admin.task.progress');
        Route::delete('task/{id}/delete','delete')->name('admin.task.delete');
        // Route::get('task/completed','completed')->name('admin.task.completed');
    });

});





