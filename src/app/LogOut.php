<?php

namespace App;

use React\Socket\Connection;

class LogOut {
    public function __invoke(Connection $conn, array $args) {
        $conn->write(get_class($this) . ': ' . json_encode($args) . PHP_EOL);
    }
}