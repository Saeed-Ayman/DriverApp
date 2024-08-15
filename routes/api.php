<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::apiResource('drivers', DriverController::class);

Route::middleware('auth:sanctum')->controller(UserController::class)->group(function () {
    Route::get('/user', 'show');
    Route::patch('/user/info', 'updateInfo');
    Route::patch('/user/avatar', 'updateAvatar');
    Route::patch('/user/password', 'updatePassword');
    Route::delete('/user', 'destroy');
});

Route::get('/login', function () {
    if (!request()->attributes->get('sanctum')) {
        abort(405);
    }

    return response()->json([
        'status' => 'error',
        'message' => 'Unauthorized',
    ], 401);
});

require __DIR__.'/auth.php';

