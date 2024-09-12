<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\LocationCategoryController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReviewsDriverController;
use App\Http\Controllers\ReviewsLocationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::apiResource('locations/countries', CountryController::class)->names("locations.countries");
Route::apiResource('locations/countries.cities', CityController::class)->names("locations.countries.cities");

Route::apiResource('drivers/countries', CountryController::class)->names("drivers.countries");
Route::apiResource('drivers/countries.cities', CityController::class)->names("drivers.countries.cities");

Route::apiResource('drivers', DriverController::class);

Route::apiResource('locations/categories', LocationCategoryController::class);
Route::apiResource('locations', LocationController::class);
Route::controller(ReviewsLocationController::class)->group(function () {
    Route::patch('locations/{location}/reviews', 'update')->name('locations.reviews.update.custom');
    Route::delete('locations/{location}/reviews', 'destroy')->name('locations.reviews.destroy.custom');
});

Route::apiResource('locations.reviews', ReviewsLocationController::class);


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

require __DIR__.'/auth.php';

