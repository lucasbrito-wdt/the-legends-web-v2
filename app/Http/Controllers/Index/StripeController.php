<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Stripe\Exception\CardException;
use Stripe\Stripe;
use Stripe\StripeClient;
use Website;

class StripeController extends Controller
{
    public function stripeKey()
    {
        return request()->json(200, ['publishableKey' => config('services.stripe.test.key')]);
    }

    function calculateOrderAmount($items)
    {
        // Replace this constant with a calculation of the order's amount
        // Calculate the order total on the server to prevent
        // people from directly manipulating the amount on the client
        return 1400;
    }

    public function pay(Request $request)
    {
        try {
            $stripe = new StripeClient(config('services.stripe.test.secret'));
            $intent = $stripe->paymentIntents->create([
                'amount' => $this->calculateOrderAmount($request->items),
                'currency' => $request->currency,
                'payment_method' => request()->input('paymentMethodId'),
                'error_on_requires_action' => true,
                'confirm' => true,
            ]);

            // The payment is complete and the money has been moved
            // You can add any post-payment code here (e.g. shipping, fulfillment, etc)

            // Send the client secret to the client to use in the demo
            echo json_encode(['clientSecret' => $intent->client_secret, 'status' => $intent->status]);
        } catch (CardException $e) {
            if ($e->getCode() == 'authentication_required') {
                echo json_encode([
                    'error' => 'This card requires authentication in order to proceeded. Please use a different card'
                ]);
            } else {
                echo json_encode([
                    'error' => $e->getMessage()
                ]);
            }
        }
    }
}
