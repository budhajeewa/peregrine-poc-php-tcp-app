<?php

require 'vendor/autoload.php';

$socket = new React\Socket\SocketServer('tls://0.0.0.0:23', array(
    'tls' => array(
        'local_cert' => __DIR__ . '/perigrine.crt',
        'local_pk' => __DIR__ . '/perigrine.key',
    )
));

$socket->on('connection', function (React\Socket\ConnectionInterface $connection) {
    echo 'Plaintext connection from ' . $connection->getRemoteAddress() . PHP_EOL;

    $routes = [
        'login' => App\LogIn::class,
        'logout' => App\LogOut::class,
        'sample' => App\Sample::class,
    ];
    
    $connection->write('hello there!' . PHP_EOL);

    $connection->on('data', function ($data) use ($connection, $routes) {
        $input = trim($data);
        $parts = explode(' ', $input);
        $routeName = strtolower($parts[0]);
        $params = array_slice($parts, 1);

        if (isset($routes[$routeName])) {
            (new $routes[$routeName])($connection, $params);
        } else {
            $connection->write("Invalid route: $routeName\n");
        }
    });

    $connection->on('end', function() {
        echo 'Connection closed.' . PHP_EOL;
    });
});