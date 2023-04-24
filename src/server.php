<?php

require 'vendor/autoload.php';

use React\EventLoop\Factory;
use React\Socket\Server;

$loop = Factory::create();
$socket = new Server('0.0.0.0:23', $loop);

echo "Server started on port 23" . PHP_EOL;

$socket->on('connection', function ($conn) {
    echo "New client connected!" . PHP_EOL;

    $routes = [
        'login' => App\LogIn::class,
        'logout' => App\LogOut::class,
        'sample' => App\Sample::class,
    ];
    
    $conn->on('data', function ($data) use ($conn, $routes) {
        $input = trim($data);
        $parts = explode(' ', $input);
        $routeName = strtolower($parts[0]);
        $params = array_slice($parts, 1);

        if (isset($routes[$routeName])) {
            (new $routes[$routeName])($conn, $params);
        } else {
            $conn->write("Invalid route: $routeName\n");
        }
    });

    $conn->on('close', function () {
        echo "Client disconnected!" . PHP_EOL;
    });
});

$loop->run();
