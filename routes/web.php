<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\reminderController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    
    Route::get('/', function () { 
        $role = Auth::user()->role;
        if($role == "admin"){
            return Redirect("/admin");
        }else{
            return Redirect("/manager");
        }
     })->name('index');

    // Routes for admin
    Route::prefix('admin')->middleware(\App\Http\Middleware\RoleAdminMiddleware::class)->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/', "index")->name('admin.index');
            Route::get('/add-user', "add_user")->name('admin.add-user');
            Route::post('/add-user', "user_store")->name('admin.add-user.store');
            Route::get('/reminder', "reminder")->name('admin.reminder');
            Route::get('/reminder/list', "reminder_list")->name('admin.reminder.list');
            Route::get('/reminder/{encodedId}/view', "reminder_view")->name('admin.reminder.view');
            Route::get('/reminder/{encodedId}/edit', "reminder_edit")->name('admin.reminder.edit');
        });

        

        Route::controller(CustomerController::class)->group(function(){
            Route::get('/customer/add', "admin_add")->name('admin.customer.add');
            Route::get('/customer/list', "admin_list")->name('admin.customer.list');
            Route::get('/customer/{encodedId}/view', "admin_view")->name('admin.customer.view');
            Route::get('/customer/list/all', "admin_list_all")->name('admin.customer.list-all');
            Route::get('/customer/overall/{encodedId}/view/', "admin_view_all")->name('admin.customer.all.view');
            Route::get('/customer/{encodedId}/edit', "admin_edit")->name('admin.customer.edit');
        });
    });

    // Routes for Manager
    Route::prefix('manager')->middleware(\App\Http\Middleware\RoleManagerMiddleware::class)->group(function () {
        Route::controller(ManagerController::class)->group(function () {
            Route::get('/', "index")->name('manager.index');
            Route::get('/profile', "profile")->name('manager.profile');
            Route::get('/invoice', "invoice")->name('manager.invoice');
            Route::get('/reminder', "reminder")->name('manager.reminder');
            Route::get('/reminder/list', "reminder_list")->name('manager.reminder.list');
            Route::get('/reminder/{encodedId}/view', "reminder_view")->name('manager.reminder.view');
            Route::get('/reminder/{encodedId}/edit', "reminder_edit")->name('manager.reminder.edit');
        });

        Route::controller(CustomerController::class)->group(function(){
            Route::get('/customer/add', "add")->name('manager.customer.add');
            Route::get('/customer/list', "list")->name('manager.customer.list');
            Route::get('/customer/{encodedId}/view', "view")->name('manager.customer.view');
            Route::get('/customer/{encodedId}/edit', "edit")->name('manager.customer.edit');
        });
    });

    // Routes for comman Admin and Manager
    Route::controller(CustomerController::class)->group(function(){
        Route::post('/add-customer', "store")->name('customer.store');
        Route::post('/customer/{encodedId}/update', "update")->name('customer.update');
        Route::post('/add-customer', "store")->name('customer.store');
        Route::delete('/customer/{encodedId}/destroy', "destroy")->name('customer.destroy');
        // Route::delete('/getAllCustomersName/{encodedUserId}/', "get")->name('customer.name.getbyusername');
    });

    Route::controller(ScheduleController::class)->group(function () {
        Route::post('/schedule/store', "store")->name('schedule.store');
        Route::post('/schedule/{id}/update', "update")->name('schedule.update');
    });

    Route::controller(reminderController::class)->group(function () {
        Route::post('/reminder/store', "store")->name('reminder.store');
        Route::post('/reminder/{encodedId}/update', "update")->name('reminder.update');
        Route::delete('/reminder/{encodedId}/destroy', "destroy")->name('reminder.destroy');
        Route::post('/reminder/is_completed', "is_completed")->name('reminder.is_completed');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Routes for guest users
Route::middleware('guest')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login',  'loginPost')->name('login.post');
    });
});

