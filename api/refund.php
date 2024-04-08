<?php

require_once 'paypal.php';

if ($_SERVER['REQUEST_METHOD'] === "GET") {
    // handle refunding an order
    $captureID = $_GET['id'];
    $response = refund_order($captureID);

    http_response_code(200);
    header('Content-Type: application/json');
    
    echo $response;
} else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method Not Allowed']);
}