<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\MeController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Clients\ClientController;
use App\Http\Controllers\Api\Projects\ProjectController;
use Illuminate\Support\Facades\Route;

Route::post('register', RegisterController::class)->name('customer.register');
Route::post('login', LoginController::class)->name('customer.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', MeController::class)->name('customer.me');
    Route::post('logout', LogoutController::class)->name('customer.logout');

    Route::apiResource('clients', ClientController::class);
    Route::apiResource('projects', ProjectController::class);
});
