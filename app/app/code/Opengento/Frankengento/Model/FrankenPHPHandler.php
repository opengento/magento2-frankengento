<?php

/**
 * FrankenPHPHandler
 *
 * @copyright Copyright © 2025 Blackbird Agency. All rights reserved.
 * @author    sebastien@bird.eu
 */

declare(strict_types=1);

namespace Opengento\Frankengento\Model;

use Monolog\Logger;
use Opengento\Frankengento\ObjectManager\ObjectManagerFactory;

class FrankenPHPHandler
{
    private static ?WorkerManager $workerManager = null;

    public static function handleRequest(): void
    {
        if (self::$workerManager === null) {
            self::$workerManager = new WorkerManager(
                new ObjectManagerFactory(),
                new Logger('franken-worker'),
                $_ENV['WORKER_COUNT'] ?? 4,
                $_REQUEST['MAX_REQUESTS'] ?? 1000
            );
        }

        // ToDo if exception no available worker, wait and try again later
        // ToDo Make sure we can actually benefit from concurrent calls here
        $response = self::$workerManager->handleRequest([
            'server' => $_SERVER,
            'get' => $_GET,
            'post' => $_POST,
            'headers' => getallheaders(),
            'cookies' => $_COOKIE
        ]);

        http_response_code($response['http_code'] ?? 200);

        foreach ($response['headers'] ?? [] as $name => $values) {
            header("{$name}: {$values}");
        }

        echo $response['content'] ?? '';
    }
}
