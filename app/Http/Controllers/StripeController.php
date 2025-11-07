<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Payment;

class StripeController extends Controller
{
    // إنشاء جلسة الدفع (requires auth token)
    public function createCheckoutSession(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => 'Example Product'],
                    'unit_amount' => 5000,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => config('app.url') . '/payment-success', // or frontend URL
            'cancel_url' => config('app.url') . '/payment-cancel',
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

return response()->json(['url' => $session->url]);    }

    // Webhook endpoint (API route, no CSRF)
    public function handleWebhook(Request $request)
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            Payment::updateOrCreate(
                ['stripe_session_id' => $session->id],
                [
                    'transaction_id' => $session->payment_intent,
                    'user_id' => $session->metadata->user_id ?? null,
                    'amount' => $session->amount_total / 100,
                    'currency' => $session->currency,
                    'status' => 'completed',
                ]
            );

            Log::info('Payment saved: ' . $session->id . ' user: ' . ($session->metadata->user_id ?? 'unknown'));
        }

        return response('Webhook received', 200);
    }
}
