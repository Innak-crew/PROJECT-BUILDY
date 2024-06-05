<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ScheduleController;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;


Route::get('/demo-mail', function () {
    try {
        Mail::to("dayanidigv954@gmail.com")->send(new SendMail("This is the dynamic content for the email."));
        return response()->json(['message' => 'Mail sent successfully.']);
    } catch (Exception $e) {
        return response()->json(['message' => 'Failed to send mail. Please try again later.', 'error' => $e->getMessage()], 500);
    }
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () { return Redirect("/admin"); })->name('index');

    Route::prefix('admin')->middleware(\App\Http\Middleware\RoleAdminMiddleware::class)->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/', "index")->name('admin.index');
        });

        Route::controller(ScheduleController::class)->group(function () {
            Route::post('/schedule/store', "store")->name('schedule.store');
            Route::post('/schedule/{id}/update', "update")->name('schedule.update');
        });
    });

    Route::prefix('manager')->middleware(\App\Http\Middleware\RoleManagerMiddleware::class)->group(function () {
        Route::controller(ManagerController::class)->group(function () {
            Route::get('/', "index")->name('manager.index');
        });
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

