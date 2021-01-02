<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use Illuminate\Http\Request;

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

// Route::middleware(['auth', 'nonPayingCustomer'])->get('subscribe', function () {
//     return view('subscribe', [
//         'intent' => auth()->user()->createSetupIntent()
//     ]);
// })->name('subscribe');

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

require __DIR__.'/auth.php';

Route::get('checkout',[CheckoutController::class, 'checkout']);
Route::post('checkout',[CheckoutController::class, 'afterpayment']); //->name('checkout.credit-card');
