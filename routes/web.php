<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
})->name('home.index');

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    if (!file_exists(public_path('storage'))) {
        Artisan::call('storage:link');
    }
    Artisan::call('optimize');

    return 'Cache cleared';
})->name('clear.cache');
