<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MasterTypePaymentController;
use App\Http\Controllers\MasterBannerController;
use App\Http\Controllers\MasterCategoryController;
use App\Http\Controllers\MasterMenuController;
use App\Http\Controllers\MasterCustomerController;
use App\Http\Controllers\MasterPositionController;
use App\Http\Controllers\MasterEmployeController;
use App\Http\Controllers\UsersController;

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
Route::resource('master_type_payment', MasterTypePaymentController::class);
Route::resource('master_position', MasterPositionController::class);
Route::resource('master_employe', MasterEmployeController::class);

Route::post('register', [UsersController::class, 'register']);
Route::post('login', [UsersController::class, 'login']);
Route::get('logout', [UsersController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

?>