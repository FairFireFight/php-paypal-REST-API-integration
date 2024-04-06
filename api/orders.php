<?php

require_once 'paypal.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // handle creating an order

    // this my be neccessary for your use case
    // $cart = json_decode($_POST['cart']); 
    $response = create_order();

    http_response_code(200);
    header('Content-Type: application/json');
    
    echo $response;
} else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method Not Allowed']);
}