<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\Ticket\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum')->name('api.logout');

Route::Group(['prefix' => '/tickets'], function () {
    Route::post('/', [ticketController::class, 'store'])->name('tickets.store');
    Route::Group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/statistics', [TicketController::class, 'index'])->name('tickets.index');
        Route::put('/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    });
});
