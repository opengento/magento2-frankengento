<?php

require __DIR__ . '/../app/autoload.php';

use Opengento\Frankengento\Model\FrankenPHPHandler;

try {
    while ($request = \frankenphp_handle_request(FrankenPHPHandler::handleRequest(...))) {
        \gc_collect_cycles();
    }
} catch (\Throwable $e) {
    echo $e->getMessage() . PHP_EOL;
}
