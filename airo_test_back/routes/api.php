<?php

use App\Http\Controllers\ConfigController;
use App\Http\Controllers\QuotationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Api Routes are registered here
|
*/

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('config', ConfigController::class);
    Route::apiResource('quotations', QuotationController::class);
    Route::post('quotations/calculate-quotation', [QuotationController::class, 'calculateQuotation']);

    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});