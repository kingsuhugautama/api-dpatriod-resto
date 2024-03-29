<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/verifikasi/{token_email}', [UsersController::class, 'verifikasiEmail'] )->name('verifikasi.email');
Route::get('test', function() {
    return json_encode(['hore world']);
});

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});
