<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PaymobService
{
    protected $apiKey;
    protected $integrationId;

    public function __construct()
    {
        $this->apiKey = config('services.paymob.api_key');
        $this->integrationId = config('services.paymob.integration_id');
    }

    /**
     * Step 1: Get Authentication Token
     */
    public function getAuthToken()
    {
        $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
            'api_key' => $this->apiKey,
        ]);

        return $response->json()['token'] ?? null;
    }

    /**
     * Step 2: Register an Order
     */
    public function createOrder($authToken, $amountCents)
    {
        $response = Http::post('https://accept.paymob.com/api/ecommerce/orders', [
            'auth_token' => $authToken,
            'delivery_needed' => 'false',
            'amount_cents' => $amountCents, // Amount in cents
            'currency' => 'EGP',
            'merchant_order_id' => rand(1000, 9999),
            'items' => [],
        ]);

        return $response->json();
    }

    /**
     * Step 3: Get Payment Key
     */
    public function getPaymentKey($authToken, $orderId, $amountCents, $billingData)
    {
        $response = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', [
            'auth_token' => $authToken,
            'amount_cents' => $amountCents,
            'expiration' => 3600,
            'order_id' => $orderId,
            'billing_data' => $billingData,
            'currency' => 'EGP',
            'integration_id' => $this->integrationId,
            'lock_order_when_paid' => 'false',
            'return_url' => route('payment.success') // Redirect after payment
        ]);

        return $response->json()['token'] ?? null;
    }

    /**
     * Get Paymob Payment Iframe URL
     */
    public function getIframeUrl($paymentKey)
    {
        return "https://accept.paymob.com/api/acceptance/iframes/" . config('services.paymob.iframe_id') . "?payment_token=" . $paymentKey;
    }
}
