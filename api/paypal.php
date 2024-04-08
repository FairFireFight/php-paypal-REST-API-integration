<?php

// you would Ideally have these switch between sandbox and production using a .env file
$baseUrl = 'https://api-m.sandbox.paypal.com';

$clientId = "YOUR CLIENT ID";
$clientSecret = "YOUR CLIENT SECRET";

function get_auth_token() {
    global $clientId, $clientSecret, $baseUrl;

    $url = $baseUrl . "/v1/oauth2/token";

    $headers = [
        "Accept: application/json",
        "Accept-Language: en_US",
        "Content-Type: application/x-www-form-urlencoded"
    ];

    $data = "grant_type=client_credentials";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);

    if(!$response) return false;

    $response = json_decode($response);
    $authToken = $response->access_token;
    curl_close($curl);

    return $authToken;
}

function create_order() {
    global $clientId, $clientSecret, $baseUrl;

    $access_token = get_auth_token();
    $url = $baseUrl . "/v2/checkout/orders";

    // array nesting hell
    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token,
    ];

    $payload = [
        'intent' => 'CAPTURE',
        'purchase_units' => [
            [
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => '200.00'
                ]
            ]
        ]
    ];

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function capture_order($orderID) {
    global $clientId, $clientSecret, $baseUrl;

    $accessToken = get_auth_token();

    $url = $baseUrl . "/v2/checkout/orders/" . $orderID . "/capture";

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer " . $accessToken
    ]);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function refund_order($captureID) {
    global $CLIENT_ID, $CLIENT_SECRET, $BASE_URL;

    $ACCESS_TOKEN = get_auth_token();
    $url = "$BASE_URL/v2/payments/captures/$captureID/refund";
    
    $ch = curl_init($url);

    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer $ACCESS_TOKEN"
    ];

    $payload = [
        "amount" => [
            "value" => "200.00",
            "currency_code" => "USD"
        ],
        "invoice_id" => "Invoice-" . substr($captureID, 5),
        "note_to_payer" => "Out of stock",
        "payment_instruction" => [
            "playform_fees" => [
                [
                    "amount" => [
                        "value" => "10",
                        "currency_code" => "USD"
                    ]
                ]
            ]
        ]
    ];

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($ch);
    curl_close($ch);

    if(!$response) return false;
    
    return $response;
}
