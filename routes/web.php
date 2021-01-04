<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/subscribe', function () {
    return view('subscribe', ['intent' => auth()->user()->createSetupIntent()]);
})->middleware(['auth', 'nonPayingCustomer'])->name('subscribe');

Route::post('/subscribe', function (Request $request) {
    // dd($request->all());
    auth()->user()->newSubscription('cashier', $request->plan)->create($request->paymentMethod);
    return redirect('/dashboard');
    // return view('subscribe');
})->middleware(['auth', 'nonPayingCustomer'])->name('subscribe.post');

Route::get('/members', function () {
    return view('members');
})->middleware(['auth', 'payingCustomer'])->name('members');

Route::get('/charge', function () {
    return view('charge', ['intent' => auth()->user()->createSetupIntent()]);
})->middleware(['auth'])->name('charge');

Route::post('/charge', function (Request $request) {

    // auth()->user()->charge(1000, $request->paymentMethod);
    auth()->user()->createAsStripeCustomer();
    auth()->user()->updateDefaultPaymentMethod($request->paymentMethod);
    auth()->user()->invoiceFor('One Time Fee', 1500);

    return redirect('/dashboard');
})->middleware(['auth'])->name('charge.post');

Route::get('/invoices', function () {
    return view('invoices', [
        'invoices' => auth()->user()->invoices()
        ]);
})->middleware(['auth'])->name('invoices');

Route::get('/user/invoice/{invoice}', function (Request $request, $invoiceId) {
    return $request->user()->downloadInvoice($invoiceId, [
        'vendor' => 'Your Company',
        'product' => 'Your Product',
    ]);
});

Route::post(
    '/stripe/webhook',
    // [WebhookController::class, 'handleWebhook']
    function () {
        Log::info('Test check from route web.php');
    }
);

require __DIR__.'/auth.php';

Route::get('checkout',[CheckoutController::class, 'checkout']);
Route::post('checkout',[CheckoutController::class, 'afterpayment']); //->name('checkout.credit-card');
