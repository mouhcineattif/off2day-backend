<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (Global)
|--------------------------------------------------------------------------
| Keep this file minimal. Modules load their own routes through their
| ServiceProviders, so don't turn this file into a junkyard.
|
*/

//
// AUTH ROUTES (global)
//
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::get('user', [AuthController::class, 'user']);

// Personal access tokens (mobile / external)
Route::get('tokens', [AuthController::class, 'tokens']);
Route::delete('tokens/{id}', [AuthController::class, 'revokeToken']);


//
// HEALTH CHECK
//
Route::get('health', fn () => ['status' => 'ok']);
