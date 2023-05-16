<?php

require_once __DIR__ . '/src/autoloader.php';
require_once __DIR__ . '/config.php';

use src\Classes\Router;

$request = [
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri' => $_SERVER['REQUEST_URI'],
    'headers' => getallheaders(),
    'body' => file_get_contents('php://input'),
];

$router = new Router();
$response = $router->route($request);

http_response_code($response['status']);

foreach ($response['headers'] as $name => $value) {
    if (is_array($value)) {
        header(sprintf('%s: %s', $name, implode(', ', $value)));

    } else {
        header(sprintf('%s: %s', $name, $value));
    }
}

echo $response['body'];
