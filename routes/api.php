<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\User\PlusUserController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\ProductController;

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


Route::post('login', [PlusUserController::class, 'authenticate']);
Route::post('register', [PlusUserController::class, 'register']);
Route::post('verify_otp', [PlusUserController::class, 'verifyOtp']);
Route::post('send_otp', [PlusUserController::class, 'emailOtp']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [PlusUserController::class, 'logout']);
    Route::post('get_user', [PlusUserController::class, 'get_user']);
    Route::post('update_rooms', [PlusUserController::class, 'updateRooms']);
    Route::post('add_invoice', [InvoiceController::class, 'AddInvoice']);
    Route::post('get_my_invoices', [InvoiceController::class, 'getMyInvoices']);
});