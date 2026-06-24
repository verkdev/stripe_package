<?php

namespace Mohsin\StripeKit\Http\Controllers;

use Illuminate\Routing\Controller;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function index()
    {
        return view('stripe-kit::stripe');
    }

    public function checkout()
    {
        Stripe::setApiKey(config('stripe-kit.secret_key'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Demo Product',
                    ],
                    'unit_amount' => 1000,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/stripe/success'),
            'cancel_url' => url('/stripe/cancel'),
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        return "Payment Success";
    }

    public function cancel()
    {
        return "Payment Cancelled";
    }
}
