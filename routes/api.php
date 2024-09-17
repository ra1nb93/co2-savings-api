<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

Route::apiResource('products', ProductController::class);
Route::apiResource('orders', OrderController::class);
Route::get('saved-co2', [OrderController::class, 'calculateSavedCO2']);
