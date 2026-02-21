<?php
ini_set('display_errors', '0');
error_reporting(E_ALL);
ob_start();

require_once __DIR__ . '/../includes/HttpHelper.php';

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'config.php';

$baseUrl = PAYPAL_API_BASE_URL;

function getPayPalAccessToken(bool $forClientToken = false): string
{
    global $baseUrl;

    if ($forClientToken) {
        $domain = defined('SITE_URL') && parse_url(SITE_URL, PHP_URL_HOST)
            ? parse_url(SITE_URL, PHP_URL_HOST)
            : 'localhost';
        $postFields = 'grant_type=client_credentials'
            . '&response_type=client_token'
            . '&domains[]=' . rawurlencode($domain);
    } else {
        $postFields = 'grant_type=client_credentials';
    }

    $ch = curl_init($baseUrl . '/v1/oauth2/token');
    curl_setopt_array($ch, [
        CURLOPT_POST            => true,
        CURLOPT_POSTFIELDS      => $postFields,
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_HTTPHEADER      => [
            'Content-Type: application/x-www-form-urlencoded',
        ],
        CURLOPT_USERPWD         => PAYPAL_CLIENT_ID . ':' . PAYPAL_CLIENT_SECRET,
        CURLOPT_TIMEOUT         => 30,
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);

    if ($err) {
        throw new Exception('PayPal token request failed: ' . $err);
    }
    if ($httpCode < 200 || $httpCode >= 300) {
        throw new Exception('PayPal token request failed: HTTP ' . $httpCode);
    }

    $data = json_decode($response, true);
    if (!is_array($data)) {
        if (preg_match('/"access_token"\s*:\s*"([^"]+)"/', (string) $response, $m)) {
            return $m[1];
        }
        if (preg_match('/"client_token"\s*:\s*"([^"]+)"/', (string) $response, $m)) {
            return $m[1];
        }
        throw new Exception('PayPal token request failed: invalid response');
    }

    $accessToken = $data['access_token'] ?? $data['client_token'] ?? null;
    if (empty($accessToken)) {
        throw new Exception('PayPal token request failed: no access_token in response');
    }
    return $accessToken;
}

header('Content-Type: application/json');

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET');
header('Access-Control-Allow-Headers: Content-Type');

$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$action = $_GET['action'] ?? null;
$isClientToken = ($_SERVER['REQUEST_METHOD'] === 'GET' && ($action === 'client-token' || strpos($requestUri, '/paypal/client-token') !== false));
$isCreateOrder = ($_SERVER['REQUEST_METHOD'] === 'POST' && ($action === 'create-order' || strpos($requestUri, '/paypal/create-order') !== false));
$captureMatches = [];
$isCapture = ($_SERVER['REQUEST_METHOD'] === 'POST' && (($action === 'capture' && !empty($_GET['order_id'])) || preg_match('#/paypal/capture/([^/]+)#', $requestUri, $captureMatches)));

if ($isClientToken) {
    try {
        $accessToken = getPayPalAccessToken(true);
        ob_clean();
        http_response_code(200);
        echo json_encode(['access_token' => $accessToken, 'clientToken' => $accessToken]);
    } catch (Throwable $e) {
        ob_clean();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

if ($isCreateOrder) {
    try {
        $token = getPayPalAccessToken();

        $input       = json_decode(file_get_contents('php://input'), true) ?? [];
        $amountValue = $input['amount']['value'] ?? '25.00';
        $currency    = $input['amount']['currency_code'] ?? 'USD';
        $customer    = $input['customer'] ?? [];

        if (!is_numeric($amountValue) || (float)$amountValue <= 0) {
            throw new Exception('Invalid amount');
        }

        $purchaseUnit = [
            'amount' => [
                'currency_code' => $currency,
                'value'         => number_format((float)$amountValue, 2, '.', '')
            ],
        ];
        if (!empty($customer['fullname']) || !empty($customer['address1'])) {
            $countryCode = 'US';
            if (!empty($customer['country'])) {
                $c = trim($customer['country']);
                $cUpper = strtoupper($c);
                if ($cUpper === 'NIGERIA' || $c === 'NG') {
                    $countryCode = 'NG';
                } elseif ($cUpper === 'UNITED STATES' || $c === 'US' || strpos($cUpper, 'UNITED STATES') !== false) {
                    $countryCode = 'US';
                }
            }
            $postalCode = trim($customer['postal_code'] ?? '');
            if ($postalCode === '' && $countryCode === 'US') {
                $postalCode = '00000';
            }
            $purchaseUnit['shipping'] = [
                'name' => [
                    'full_name' => !empty($customer['fullname']) ? $customer['fullname'] : 'Customer',
                ],
                'address' => [
                    'address_line_1' => $customer['address1'] ?? '',
                    'admin_area_1'   => $customer['state'] ?? '',
                    'admin_area_2'   => $customer['state'] ?? '',
                    'postal_code'    => $postalCode !== '' ? $postalCode : '000001',
                    'country_code'   => $countryCode,
                ],
            ];
        }

        $body = [
            'intent'          => 'CAPTURE',
            'purchase_units'  => [$purchaseUnit],
            'payment_source'  => [
                'paypal' => [
                    'experience_context' => [
                        'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
                        'landing_page'              => 'LOGIN',
                        'user_action'               => 'PAY_NOW',
                        'shipping_preference'       => !empty($purchaseUnit['shipping']) ? 'SET_PROVIDED_ADDRESS' : 'NO_SHIPPING',
                    ]
                ]
            ]
        ];

        $ch = curl_init($baseUrl . '/v2/checkout/orders');
        curl_setopt_array($ch, [
            CURLOPT_POST            => true,
            CURLOPT_POSTFIELDS      => json_encode($body),
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER      => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
                'PayPal-Request-Id: ' . uniqid('req_', true),
                'Prefer: return=representation',
            ],
            CURLOPT_TIMEOUT         => 30,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            throw new Exception('Failed to create PayPal order: ' . $err);
        }
        if ($httpCode < 200 || $httpCode >= 300) {
            $decoded = json_decode($response, true);
            $msg = is_array($decoded) ? ($decoded['message'] ?? $decoded['name'] ?? 'HTTP ' . $httpCode) : 'HTTP ' . $httpCode;
            throw new Exception('Failed to create PayPal order: ' . $response);
        }

        $data = json_decode($response, true);
        $orderId = is_array($data) && !empty($data['id']) ? $data['id'] : null;
        if (!$orderId) {
            throw new Exception('Failed to create PayPal order: no order id in response');
        }

        ob_clean();
        http_response_code(200);
        echo json_encode(['id' => $orderId]);

    } catch (Throwable $e) {
        ob_clean();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

if ($isCapture) {
    try {
        $orderId = isset($captureMatches[1]) ? $captureMatches[1] : ($_GET['order_id'] ?? '');
        if (!$orderId) {
            throw new Exception('Missing order ID');
        }

        $token = getPayPalAccessToken();

        $result = HttpHelper::make()
            ->withHeaders([
                'Authorization'     => 'Bearer ' . $token,
                'PayPal-Request-Id' => uniqid('req_', true),
                'Prefer'            => 'return=representation',
            ])
            ->post($baseUrl . "/v2/checkout/orders/$orderId/capture");

        if (!$result['success']) {
            throw new Exception($result['error'] ?? 'Failed to capture PayPal order');
        }

        ob_clean();
        http_response_code(200);
        echo json_encode($result['data']);

    } catch (Throwable $e) {
        ob_clean();
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Endpoint not found']);
