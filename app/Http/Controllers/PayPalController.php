<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PayPalController extends Controller
{
    private function getAccessToken(bool $forClientToken = false): string
    {
        $baseUrl = config('services.paypal.url');
        $body = ['grant_type' => 'client_credentials'];
        if ($forClientToken) {
            $body['response_type'] = 'client_token';
            $body['domains[]'] = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        }

        $response = Http::withBasicAuth(config('services.paypal.client_id'), config('services.paypal.client_secret'))
            ->asForm()
            ->post($baseUrl.'/v1/oauth2/token', $body);

        if (! $response->successful()) {
            throw new \Exception('PayPal token request failed: HTTP '.$response->status());
        }
        $data = $response->json();

        return $data['access_token'] ?? $data['client_token'] ?? throw new \Exception('No access token in response');
    }

    public function clientToken(Request $request)
    {
        if ($request->get('action') !== 'client-token') {
            return $this->handleApi($request);
        }
        try {
            $token = $this->getAccessToken(true);

            return response()->json(['access_token' => $token, 'clientToken' => $token]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handleApi(Request $request)
    {
        $action = $request->get('action');

        if ($action === 'create-order') {
            return $this->createOrder($request);
        }
        if ($action === 'capture' && $request->get('order_id')) {
            return $this->capture($request->get('order_id'));
        }

        return response()->json(['error' => 'Endpoint not found'], 404);
    }

    private function createOrder(Request $request)
    {
        try {
            $input = $request->all();
            $amountValue = $input['amount']['value'] ?? '25.00';
            $currency = $input['amount']['currency_code'] ?? 'USD';
            $customer = $input['customer'] ?? [];

            if (! is_numeric($amountValue) || (float) $amountValue <= 0) {
                throw new \Exception('Invalid amount');
            }

            $purchaseUnit = [
                'amount' => [
                    'currency_code' => $currency,
                    'value' => number_format((float) $amountValue, 2, '.', ''),
                ],
            ];

            if (! empty($customer['fullname']) || ! empty($customer['address1'])) {
                $countryCode = 'US';
                if (! empty($customer['country'])) {
                    $c = strtoupper(trim($customer['country']));
                    if (str_contains($c, 'NIGERIA') || $c === 'NG') {
                        $countryCode = 'NG';
                    } elseif (str_contains($c, 'UNITED STATES') || $c === 'US') {
                        $countryCode = 'US';
                    }
                }
                $postalCode = trim($customer['postal_code'] ?? '') ?: ($countryCode === 'US' ? '00000' : '000001');
                $purchaseUnit['shipping'] = [
                    'name' => ['full_name' => $customer['fullname'] ?? 'Customer'],
                    'address' => [
                        'address_line_1' => $customer['address1'] ?? '',
                        'admin_area_1' => $customer['state'] ?? '',
                        'admin_area_2' => $customer['state'] ?? '',
                        'postal_code' => $postalCode,
                        'country_code' => $countryCode,
                    ],
                ];
            }

            $baseUrl = config('services.paypal.url');
            $response = Http::withToken($this->getAccessToken())
                ->withHeaders(['PayPal-Request-Id' => uniqid('req_', true), 'Prefer' => 'return=representation'])
                ->post($baseUrl.'/v2/checkout/orders', [
                    'intent' => 'CAPTURE',
                    'purchase_units' => [$purchaseUnit],
                    'payment_source' => [
                        'paypal' => [
                            'experience_context' => [
                                'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
                                'landing_page' => 'LOGIN',
                                'user_action' => 'PAY_NOW',
                                'shipping_preference' => isset($purchaseUnit['shipping']) ? 'SET_PROVIDED_ADDRESS' : 'NO_SHIPPING',
                            ],
                        ],
                    ],
                ]);

            if (! $response->successful()) {
                throw new \Exception('Failed to create PayPal order: '.($response->json('message') ?? 'HTTP '.$response->status()));
            }
            $orderId = $response->json('id');
            if (! $orderId) {
                throw new \Exception('No order id in response');
            }

            return response()->json(['id' => $orderId]);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function capture(string $orderId)
    {
        try {
            $baseUrl = config('services.paypal.url');
            $response = Http::withToken($this->getAccessToken())
                ->withHeaders(['PayPal-Request-Id' => uniqid('req_', true), 'Prefer' => 'return=representation'])
                ->post($baseUrl."/v2/checkout/orders/{$orderId}/capture");

            if (! $response->successful()) {
                throw new \Exception($response->json('message') ?? 'Failed to capture');
            }

            return response()->json($response->json());
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
