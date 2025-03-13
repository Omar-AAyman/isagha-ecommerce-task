<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $paymob;

    public function __construct(PaymobService $paymob)
    {
        $this->paymob = $paymob;
    }

    /**
     * Initiate Checkout Process
     */
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $amountCents = $request->amount * 100; // Convert to cents

        // Step 1: Get authentication token from Paymob
        $authToken = $this->paymob->getAuthToken();

        if (!$authToken) {
            return redirect()->back()->with('error', 'Failed to authenticate with Paymob');
        }

        // Step 2: Create an order in Paymob
        $paymobOrder = $this->paymob->createOrder($authToken, $amountCents);

        if (!isset($paymobOrder['id'])) {
            return redirect()->back()->with('error', 'Failed to create order');
        }

        // Step 3: Save the order in the database
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $amountCents / 100, // Convert back to normal currency
            'payment_status' => 'pending',
            'payment_reference' => $paymobOrder['id'],
        ]);

        // Step 4: Get payment key
        $billingData = [
            "first_name" => $user->name ?? "Test",
            "last_name" => "User",
            "email" => $user->email ?? "test@example.com",
            "phone_number" => "01012345678",
            "apartment" => "NA",
            "floor" => "NA",
            "street" => "Test Street",
            "building" => "NA",
            "city" => "Cairo",
            "state" => "Helwan",
            "country" => "EG",
        ];

        $paymentKey = $this->paymob->getPaymentKey($authToken, $paymobOrder['id'], $amountCents, $billingData);

        if (!$paymentKey) {
            return redirect()->back()->with('error', 'Failed to get payment key');
        }

        // Step 5: Redirect to Paymob iframe
        return redirect()->away($this->paymob->getIframeUrl($paymentKey));
    }

    /**
     * Handle Payment Webhook from Paymob
     */
    public function handleWebhook(Request $request)
    {
        Log::info('Paymob Webhook Received', $request->all());

        $data = $request->all();
        $paymobHmac = $data['hmac'] ?? null;

        if (!$paymobHmac) {
            Log::error("HMAC not provided");
            return redirect()->route('payment.failure')->with('error', 'HMAC not provided');
        }

        // Fields in the exact order specified by Paymob
        $fields = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];

        // Generate concatenated string
        $concatenatedString = '';

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $concatenatedString .= $data[$field];
            }
        }

        // Load the secret key
        $hmacSecret = env('PAYMOB_HMAC_SECRET');

        if (!$hmacSecret) {
            Log::error("PAYMOB_HMAC_SECRET is not set in .env");
            return redirect()->route('payment.failure')->with('error', 'Server configuration error');
        }

        // Calculate HMAC
        $calculatedHmac = hash_hmac('sha512', $concatenatedString, $hmacSecret);

        // Log the calculated HMAC for debugging
        Log::info("HMAC Validation", [
            'expected' => $paymobHmac,
            'calculated' => $calculatedHmac,
            'concatenated_string' => $concatenatedString,
        ]);

        if (!hash_equals($paymobHmac, $calculatedHmac)) {
            Log::error("HMAC validation failed");
            return redirect()->route('payment.failure')->with('error', 'HMAC validation failed');
        }

        // Process the webhook since HMAC is valid
        Log::info("Webhook is secure and verified!");

        $success = filter_var($data['success'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $paymobOrderId = $data['order'] ?? null;
        $amountCents = ($data['amount_cents'] ?? 0) / 100;

        if (!$paymobOrderId) {
            Log::error("Order ID missing in webhook");
            return redirect()->route('payment.failure')->with('error', 'Order ID missing');
        }

        $order = Order::where('payment_reference', $paymobOrderId)->first();

        if (!$order) {
            Log::error("Order with Paymob ID {$paymobOrderId} not found.");
            return redirect()->route('payment.failure')->with('error', 'Order not found');
        }

        if ($order->payment_status === 'paid') {
            return redirect()->route('payment.success')->with('success', 'Order already paid');
        }

        $order->update([
            'payment_status' => $success ? 'paid' : 'failed',
            'paid_amount' => $success ? $amountCents : 0,
            'payment_method' => $data['source_data_sub_type'] ?? 'unknown',
        ]);

        if ($success) {
            CartItem::where('user_id', $order->user_id)->delete();
            return redirect()->route('payment.success')->with('success', 'Payment successful. Your order will be shipped soon.');
        }
        return redirect()->route('payment.failure')->with('error', 'Payment failed. Please try again.');
    }



    public function paymentSuccess()
    {
        return view('payment.success')->with('message', session('success'));
    }

    public function paymentFailure()
    {
        return view('payment.failure')->with('message', session('error'));
    }
}
