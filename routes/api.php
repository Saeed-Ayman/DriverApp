<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/user', function (Request $request) {
    return auth()->user();
})->middleware('auth:sanctum');

require __DIR__.'/auth.php';

