<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ItemController;

use App\Http\Controllers\Api\AuthController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('items', ItemController::class);
    Route::post('items/{id}/add-stock', [ItemController::class, 'addStock']);
    Route::post('items/{id}/remove-stock', [ItemController::class, 'removeStock']);
});