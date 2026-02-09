<?php

use App\Http\Controllers\Api\v1\Ticket\TicketController;
use App\Http\Controllers\Web\AdminPanel\AdminPanelController;
use App\Http\Controllers\Web\AdminPanel\UserController;
use \App\Http\Controllers\Web\AdminPanel\TicketController as AdminPanelTicketController;
use App\Http\Controllers\Web\Auth\AuthController;
use App\Http\Controllers\Web\Widget\WidgetController;
use App\Http\Middleware\EnsureCanManageTickets;
use App\Http\Middleware\EnsureCanManageUsers;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome') ;

Route::get('/feedback-widget', [WidgetController::class, 'get'])->name('feedback-widget');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth.basic', 'prefix' => '/admin'], function () {
    Route::get("/dashboard", [AdminPanelController::class, 'dashboard'])->name('admin.dashboard');

    Route::group(['prefix' => '/users', 'middleware' => EnsureCanManageUsers::class], function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::get('/show/{user}', [UserController::class, 'show'])->name('admin.users.show');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::patch('/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::post('/', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/{user}/reset-password', [UserController::class, 'resetPassword'])->name('admin.users.reset-password');
        Route::put('/{user}', [UserController::class, 'updatePassword'])->name('admin.users.updatePassword');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    });

    Route::group(['prefix' => '/tickets', 'middleware' => EnsureCanManageTickets::class], function () {
        Route::get('/', [AdminPanelTicketController::class, 'index'])->name('admin.tickets.index');
        Route::get('/{ticket}', [AdminPanelTicketController::class, 'edit'])->name('admin.tickets.edit');
    });
});
