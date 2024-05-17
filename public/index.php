<?php

$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$routes = [
    '/' => '../controllers/login.php',
    '/receipt' => '../controllers/receiptForm.php',
    '/data' => '../controllers/data.php'
];

if(array_key_exists($requestPath, $routes)){
    require $routes[$requestPath];
}
else{
    http_response_code(404);
    echo "404 - Page not found. Please try again.";
}
