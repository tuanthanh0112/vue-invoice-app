<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get_all_invoice', [InvoiceController::class, 'get_all_invoice']);
Route::get('/search_invoice', [InvoiceController::class, 'search_invoice']);
Route::get('/create_invoice', [InvoiceController::class, 'create_invoice']);
Route::get('/customers', [CustomerController::class, 'get_all_customer']);
Route::get('/products', [ProductController::class, 'all_products']);
Route::post('/add-invoice', [InvoiceController::class, 'add_invoice']);
Route::get('/show_invoice/{id}', [InvoiceController::class, 'getDetailinvoice']);
Route::get('/edit_invoice/{id}', [InvoiceController::class, 'editInvoice']);
Route::get('/detele_invoice/{id}', [InvoiceController::class, 'deteleInvoiceItems']);
Route::post('/update_invoice/{id}', [InvoiceController::class, 'updateInvoiceItems']);
Route::get('/delete_invoice/{id}', [InvoiceController::class, 'deleteInvoice']);
