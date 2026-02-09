<?php

use App\Http\Controllers\Web\WidgetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/feedback-widget', [WidgetController::class, 'get']);
