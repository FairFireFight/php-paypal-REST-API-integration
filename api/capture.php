<?php

require_once 'paypal.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // handle capturing an order
    $orderID = $_GET['id'];
    $response = capture_order($orderID);

    http_response_code(200);
    header('Content-Type: application/json');

    echo $response;
} else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method Not Allowed']);
}
