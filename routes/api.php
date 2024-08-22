<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\ReviewsDriverController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::apiResource('drivers', DriverController::class);

Route::controller(ReviewsDriverController::class)->group(function () {
    Route::patch('drivers/{driver}/reviews', 'update')->name('drivers.reviews.update.custom');
    Route::delete('drivers/{driver}/reviews', 'destroy')->name('drivers.reviews.destroy.custom');
});

Route::apiResource('drivers.reviews', ReviewsDriverController::class);

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

Route::get('/u', function () {
   return \App\Models\User::first()
       ->createToken('test')
       ->plainTextToken;
});

require __DIR__.'/auth.php';

