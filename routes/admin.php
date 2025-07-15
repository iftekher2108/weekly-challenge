<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

Auth::routes();


Route::controller(HomeController::class)->group(function() {
    Route::get('/dashboard','index')->name('admin.dashboard');
});

Route::middleware('auth')->controller(CategoryController::class)->group(function() {
    Route::get('/category','index')->name('admin.category');
    
});


