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

function handleResponse($response) {
    try {
        $jsonResponse = json_decode($response, true);
        return [
            'jsonResponse' => $jsonResponse,
            'httpStatusCode' => http_response_code()
        ];
    } catch (Exception $e) {
        $errorMessage = $response->getBody()->getContents();
        throw new Exception($errorMessage);
    }
}