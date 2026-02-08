<?php

use App\Http\Controllers\Api\v1\Ticket\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::Group(['prefix' => '/tickets'], function () {
    Route::post('/', [ticketController::class, 'store'])->name('tickets.store');
    Route::get('/statistics', [TicketController::class, 'index'])->name('tickets.index');
});
