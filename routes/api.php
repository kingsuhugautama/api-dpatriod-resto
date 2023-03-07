<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterBannerController;
use App\Http\Controllers\MasterCategoryController;
use App\Http\Controllers\MasterMenuController;
use App\Http\Controllers\MasterCustomerController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::resource('master_banner', MasterBannerController::class);
Route::resource('master_category', MasterCategoryController::class);
Route::resource('master_menu', MasterMenuController::class);
Route::resource('master_customer', MasterCustomerController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
