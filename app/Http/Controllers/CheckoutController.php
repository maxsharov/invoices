<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout()
    {   
        // Enter Your Stripe Secret
        // \Stripe\Stripe::setApiKey('use-your-stripe-key-here');
        		
		// $amount = 100;
		// $amount *= 100;
        // $amount = (int) $amount;
        
        // $payment_intent = \Stripe\PaymentIntent::create([
		// 	'description'   => 'Stripe Test Payment',
		// 	'amount'        => $amount,
		// 	'currency'      => 'USD',
		// 	'description'   => 'Invoice Payment',
		// 	'payment_method_types' => ['card'],
		// ]);
		// $intent = $payment_intent->client_secret;

		// return view('checkout.credit-card',compact('intent'));

    }

    public function afterPayment()
    {
        dd(request()->all());
        // echo 'Payment Has been Received';
    }
}