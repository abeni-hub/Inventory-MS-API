<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\PurchaseOrderController;
use App\Http\Controllers\Api\PurchaseOrderItemController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::apiResource('items', ItemController::class);
    Route::post('items/{id}/add-stock', [ItemController::class, 'addStock']);
    Route::post('items/{id}/remove-stock', [ItemController::class, 'removeStock']);
    Route::apiResource('categories', CategoryController::class);
    Route::get('reports/low-stock', [ReportController::class, 'lowStock']);
    Route::get('reports/total-value', [ReportController::class, 'totalValue']);
    Route::get('reports/category-summary', [ReportController::class, 'categorySummary']);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('purchase-orders', PurchaseOrderController::class);
    Route::post('purchase-orders/{id}/receive', [PurchaseOrderController::class, 'receive']);
    Route::apiResource('purchase-orders.items', PurchaseOrderItemController::class)->shallow();
    
});