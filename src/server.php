<?php

require 'vendor/autoload.php';

// use React\EventLoop\Factory;
// use React\Socket\Server;

// $loop = Factory::create();
// $socket = new Server('0.0.0.0:23', $loop);

// echo "Server started on port 23" . PHP_EOL;

// $socket->on('connection', function ($conn) {
//     echo "New client connected!" . PHP_EOL;

//     $routes = [
//         'login' => App\LogIn::class,
//         'logout' => App\LogOut::class,
//         'sample' => App\Sample::class,
//     ];
    
//     $conn->on('data', function ($data) use ($conn, $routes) {
//         $input = trim($data);
//         $parts = explode(' ', $input);
//         $routeName = strtolower($parts[0]);
//         $params = array_slice($parts, 1);

//         if (isset($routes[$routeName])) {
//             (new $routes[$routeName])($conn, $params);
//         } else {
//             $conn->write("Invalid route: $routeName\n");
//         }
//     });

//     $conn->on('close', function () {
//         echo "Client disconnected!" . PHP_EOL;
//     });
// });

// $loop->run();

$socket = new React\Socket\SocketServer('tls://0.0.0.0:23', array(
    'tls' => array(
        'local_cert' => __DIR__ . '/perigrine.crt',
        'local_pk' => __DIR__ . '/perigrine.key',
    )
));

$socket->on('connection', function (React\Socket\ConnectionInterface $connection) {
    echo 'Plaintext connection from ' . $connection->getRemoteAddress() . PHP_EOL;
    
    $connection->write('hello there!' . PHP_EOL);

    $connection->on('data', function($data) use ($connection) {
        echo 'In: ' . $data . PHP_EOL;
        $connection->write( '= ' . $data . PHP_EOL );
    });

    $connection->on('end', function() {
        echo 'Connection closed.' . PHP_EOL;
    });
});