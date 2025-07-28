<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::middleware(['auth'])->group(function() {
    Route::controller(HomeController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('admin.dashboard');
        Route::get('profile','profile')->name('admin.profile');
    });

    Route::put('profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');

    Route::middleware('role:admin,super-admin')->controller(CategoryController::class)->group(function () {
      Route::get('/category/company','companySearch')->name('admin.category.company');
        Route::get('/category', 'index')->name('admin.category');
        Route::post('category/store', 'store')->name('admin.category.store');
        Route::get('category/{id}/edit','edit')->name('admin.category.edit');
        Route::put('category/{id}/update','update')->name('admin.category.update');
        Route::delete('category/{id}/delete','delete')->name('admin.category.delete');
    });

    Route::controller(TaskController::class)->group(function() {
        Route::get('task','index')->name('admin.task');
        Route::post('task/store','store')->name('admin.task.store')->middleware('role:admin,super-admin');
        Route::get('task/{id}/edit','edit')->name('admin.task.edit')->middleware('role:admin,super-admin');
        Route::put('task/{id}/update','update')->name('admin.task.update')->middleware('role:admin,super-admin');
        Route::put('task/{id}/progress','taskProgress')->name('admin.task.progress')->middleware('role:admin,super-admin');
        Route::delete('task/{id}/delete','delete')->name('admin.task.delete')->middleware('role:admin,super-admin');
        // Route::get('task/completed','completed')->name('admin.task.completed');
    });

    Route::controller(ReportController::class)->group(function(){
        Route::get('/report', 'index')->name('admin.report')->middleware('role:user,admin,super-admin');
        Route::get('/report/company/{companyId}', 'companyReport')->name('admin.report.company')->middleware('role:super-admin,admin');
    });

    // Company Management Routes
    Route::middleware('role:super-admin')->controller(CompanyController::class)->group(function() {
        Route::get('/company', 'index')->name('admin.company.index')->middleware('role:super-admin,admin');
        Route::get('/company/create', 'create')->name('admin.company.create')->middleware('role:super-admin');
        Route::post('/company/store', 'store')->name('admin.company.store')->middleware('role:super-admin');
        Route::get('/company/{company}', 'show')->name('admin.company.show')->middleware('role:super-admin,admin');
        Route::get('/company/{company}/edit', 'edit')->name('admin.company.edit')->middleware('role:super-admin,admin');
        Route::put('/company/{company}/update', 'update')->name('admin.company.update')->middleware('role:super-admin,admin');
        Route::delete('/company/{company}/delete', 'destroy')->name('admin.company.delete')->middleware('role:super-admin');
        Route::get('/company/{company}/users', 'users')->name('admin.company.users')->middleware('role:super-admin,admin');
    });

    // User Management Routes
    Route::controller(UserManagementController::class)->group(function() {
        Route::get('/user', 'index')->name('admin.user.index')->middleware('role:super-admin,admin');
        Route::get('/user/create', 'create')->name('admin.user.create')->middleware('role:super-admin,admin');
        Route::post('/user/store', 'store')->name('admin.user.store')->middleware('role:super-admin,admin');
        Route::get('/user/{user}', 'show')->name('admin.user.show')->middleware('role:super-admin,admin');
        Route::get('/user/{user}/edit', 'edit')->name('admin.user.edit')->middleware('role:super-admin,admin');
        Route::put('/user/{user}/update', 'update')->name('admin.user.update')->middleware('role:super-admin,admin');
        Route::delete('/user/{user}/delete', 'destroy')->name('admin.user.delete')->middleware('role:super-admin,admin');
    });

    Route::get('/user/unassigned', [UserManagementController::class, 'unassigned'])->name('admin.user.unassigned')->middleware('role:super-admin,admin');


});





