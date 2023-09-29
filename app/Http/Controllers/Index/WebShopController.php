<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Otserver\Account;
use Otserver\Player;
use Otserver\Transactions;
use Otserver\Visitor;
use Otserver\Website;
use Stripe\Stripe;
use Stripe\StripeClient;

class WebShopController extends Controller
{
    public function index()
    {
        $account_logged = Visitor::getAccount();
        $logged = Visitor::isLogged();
        $errorsView = false;
        $view = 'index.webshop.index';

        if (!$logged) {
            $errorsView = true;
            return view($view, [
                'account_logged' => $account_logged,
                'errorsView' => $errorsView
            ])->withErrors('<p class="text-center m-0">Você não está logado, <a href="' . route('accountmanagement.login') . '">click aqui</a>  para fazer login.</p>');
        }

        return view($view, [
            'account_logged' => $account_logged,
            'errorsView' => $errorsView
        ]);
    }

    private function generateItems($items = [], $currency = 'brl')
    {
        $payment = [];
        foreach ($items as $item) {
            $payment[] = [
                'price_data' => [
                    'currency' => $currency,
                    'unit_amount' => str_replace('.', '', $item['amount']),
                    'product_data' => [
                        'name' => $item['label'],
                        'images' => [$item['image']]
                    ],
                ],
                'quantity' => 1,
            ];
        }
        return $payment;
    }

    public function calculateOrderPoints($items = [])
    {
        $points = 0;
        foreach ($items as $item)
            $points += $item['points'];
        return $points;
    }

    public function checkout(Request $request)
    {
        $account_logged = Visitor::getAccount();
        try {
            $stripe = new StripeClient(config('services.stripe.secret'));
            $checkout_session = $stripe->checkout->sessions->create([
                'payment_method_types' => ['card'],
                'line_items' => $this->generateItems($request->input('items')),
                'mode' => 'payment',
                'success_url' => '' .  $request->input('successPage') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => '' . $request->input('cancelPage') . '',
            ]);

            $transactions = new Transactions();
            $transactions->setAccountId($account_logged->getID());
            $transactions->setPaymentMethod('card');
            if (!empty($request->input('accountFrom'))) {
                $player = new Player(urldecode($request->input('accountFrom')), Player::LOADTYPE_NAME);
                $transactions->setAccountFrom($player->getAccountID());
            }
            $transactions->setPoints($this->calculateOrderPoints($request->input('items')));
            $transactions->setTransactionCode($checkout_session->id);
            $transactions->save();

            echo json_encode(['id' => $checkout_session->id]);
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function finish(Request $request)
    {
        $session_id = $request->input('session_id');
        $stripe = new StripeClient(config('services.stripe.secret'));
        $session = $stripe->checkout->sessions->retrieve($session_id);
        $paymentIntents = $stripe->paymentIntents->retrieve($session->payment_intent);
        $transactions = new Transactions($session_id, Transactions::LOADTYPE_TRANSACTION_CODE);

        if (empty($transactions->getAccountFrom()))
            $account = new Account($transactions->getAccountId());
        else
            $account = new Account($transactions->getAccountFrom());

        if ($transactions->isLoaded()) {
            if ($account->isLoaded()) {
                if ($paymentIntents->status == "succeeded" && $transactions->getStatus() != "succeeded") {
                    $account->setPremiumPoints($account->getPremiumPoints() + (int)$transactions->getPoints());
                    $account->save();

                    $transactions->setStatus($paymentIntents->status);
                    $transactions->save();
                } else {
                    $transactions->setStatus($paymentIntents->status);
                    $transactions->save();
                }
            }
        }

        return view('index.webshop.finish');
    }
}
