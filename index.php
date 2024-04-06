<?php
require_once 'api/paypal.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Buy</title>
    </head>
    <body>
       <h1>Store</h1> 
       <hr>
       <h2>Funny agony face NFT - $200</h2>
       <img src="despair.png" width="200"></br>
       <div id="paypal-button-container"></div>
       <div id="result-message"></div>

       <script src="https://www.paypal.com/sdk/js?client-id=<?= $clientId ?>"></script>
       <script src="index.js"></script>
    </body>
</html>