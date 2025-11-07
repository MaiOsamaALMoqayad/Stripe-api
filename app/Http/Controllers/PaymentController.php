<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function confirmPayment(Request $request)
    {
        $sessionId = $request->input('session_id');

        // تحقق من الجلسة من Stripe (نفس المكتبة اللي استخدمناها)
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            if ($session->payment_status === 'paid') {
                return response()->json([
                    'message' => 'Payment successful',
                    'session' => $session,
                ]);
            }

            return response()->json([
                'message' => 'Payment not completed yet',
                'status' => $session->payment_status,
            ], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
