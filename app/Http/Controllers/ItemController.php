<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Session;
use Stripe;

use App\Mail\Invoice;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Item::orderBy('created_at', 'DESC')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        $newItem = new Item;
        $newItem->name = $request->item['name'];
        $newItem->email = $request->item['email'];
        $newItem->amount = $request->item['amount'];
        $newItem->save();

        return $newItem;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $existingItem = Item::find($id);
        
        if ( $existingItem ) {
            $existingItem->is_payed = $request->item['is_payed'] ? true : false;
            $existingItem->payed_at = $request->item['is_payed'] ? Carbon::now() : null;
            $existingItem->save();
            return $existingItem;
        }

        return 'Item not found.';

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $existingItem = Item::find($id);

        if ( $existingItem ) {
            $existingItem->delete();
            return "Item successfully deleted";
        }

        return 'Item not found.';
    }

    public function sendEmail($id) 
    {
        $existingItem = Item::find($id);
        if ( $existingItem ) {
            Mail::to('max.sharov@gmail.com')->send(new Invoice($existingItem));
        }

        return 'Item not found.';
    }


    public function initPayment($id) {
        $existingItem = Item::find($id);
        if ( $existingItem ) {
            Stripe\Stripe::setApiKey('sk_test_51I3yzgK9YNhLPRz6P6qSFHj8k3ODe4VDDCwBpJbcAoeTfbWrGWZRcBKsMGkwXFlmLQ96VAdbUKQrj71EzDd1cQyN00KErYvfjk');

            $payment_intent = Stripe\PaymentIntent::create([
                'description'   => 'Stripe Test Payment',
                'amount'        => $existingItem->amount * 100,
                'currency'      => 'USD',
                'receipt_email' => $existingItem->email,
                'description'   => 'Invoice Payment',
                'payment_method_types' => ['card'],
                'receipt_email' => 'max.sharov@gmail.com',
            ]);
            
            // dd($payment_intent);

            $intent = $payment_intent->client_secret;
            $amount = $existingItem->amount;
            return view('checkout.credit-card', compact('intent', 'amount'));
            // Session::flash('success', 'Payment successful!');

            // print_r($result);

        }

        return 'Item not found.';
    }
}
