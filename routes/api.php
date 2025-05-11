<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
Route::get('/payment-sucess', [PaymentController::class,'success'])->name('payment-success');
Route::post('/doku/callback', function (Request $request) {
    // Log request for debugging
    Log::info('DOKU Webhook Data:', $request->all());

    // Process transaction status
    $status = $request->input('transaction_status');
    $invoiceNumber = $request->input('invoice_number');

    // Example: Update database (adjust according to your table structure)
    // $order = \App\Models\Order::where('invoice_number', $invoiceNumber)->first();
    // if ($order) {
    //     $order->status = $status;
    //     $order->save();
    // }

    return response()->json(['message' => 'Webhook received successfully']);
});