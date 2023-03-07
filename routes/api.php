<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuantityController;
use App\Http\Controllers\SystemEventController;
use App\Http\Controllers\TraderController;
use App\Http\Controllers\WalletOperationController;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix("/auth")->group(function () {
    Route::post('login', [AuthController::class, "login"]);
});
Route::apiResource('currency', CurrencyController::class);
Route::apiResource('category', CategoryController::class);
Route::apiResource('expense', ExpenseController::class);
Route::apiResource('material', CategoryController::class);
Route::apiResource('product', ProductController::class);
Route::apiResource('quantity', QuantityController::class);
Route::apiResource('branch', BranchController::class);
Route::apiResource('trader', TraderController::class);
Route::apiResource("events", SystemEventController::class);
Route::apiResource("wallet-operation", WalletOperationController::class);


Route::get("config", [\App\Http\Controllers\ConfigController::class, 'getProductExtras']);
Route::post("add_product_variant", [\App\Http\Controllers\ConfigController::class, 'addProductVariant']);
