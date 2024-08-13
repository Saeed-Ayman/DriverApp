<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/user', [UserController::class, 'show'])->middleware('auth:sanctum');
Route::patch('/user/info', [UserController::class, 'updateInfo'])->middleware('auth:sanctum');
Route::patch('/user/avatar', [UserController::class, 'updateAvatar'])->middleware('auth:sanctum');
Route::patch('/user/password', [UserController::class, 'updatePassword'])->middleware('auth:sanctum');
Route::delete('/user', [UserController::class, 'destroy'])->middleware('auth:sanctum');

require __DIR__.'/auth.php';

