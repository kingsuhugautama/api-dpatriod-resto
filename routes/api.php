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
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\TransOrderController;
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
Route::delete('master_category/delete/{id}', [MasterCategoryController::class,'destroy']);

Route::resource('master_menu', MasterMenuController::class);
Route::delete('master_menu/delete/{id}', [MasterMenuController::class,'destroy']);
Route::post('master_menu/search', [MasterMenuController::class,'search']);
Route::post('master_menu/search_menu', [MasterMenuController::class,'searchMenu']);

Route::resource('master_customer', MasterCustomerController::class);
Route::delete('master_customer/delete/{id}', [MasterCustomerController::class,'destroy']);

Route::resource('master_type_payment', MasterTypePaymentController::class);

Route::resource('master_position', MasterPositionController::class);
Route::delete('master_position/delete/{id}', [MasterPositionController::class,'destroy']);

Route::resource('master_employe', MasterEmployeController::class);
Route::post('master_employe/login', [MasterEmployeController::class,'login']);

Route::post('order/save', [TransOrderController::class, 'order']);
Route::put('order/update_status_detail_order/{id}', [TransOrderController::class, 'update']);
Route::get('order/list_pesanan_detail', [TransOrderController::class, 'detail']);
Route::get('order/list_pesanan', [TransOrderController::class, 'index']);
Route::get('order/history/{id_customer}', [TransOrderController::class, 'history']);
Route::get('order/get_detail_by_id_order/{id_order}', [TransOrderController::class, 'get_detail_by_id_order']);
Route::put('order/update_paid/{id_order}', [TransOrderController::class, 'update_paid']);

Route::post('order/report', [TransOrderController::class, 'report']);
Route::post('order/report_today', [TransOrderController::class, 'reportToday']);


Route::post('register', [UsersController::class, 'register']);
Route::post('login', [UsersController::class, 'login']);
Route::get('logout', [UsersController::class, 'logout']);

Route::get('payment/register',[PaymentGatewayController::class,'registrasi']);
Route::post('payment/notification',[PaymentGatewayController::class,'notification']);
Route::get('payment/callBackUrl',[PaymentGatewayController::class,'callBackUrl']);
Route::post('payment/callBackUrl',[PaymentGatewayController::class,'callBackUrl']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

?>