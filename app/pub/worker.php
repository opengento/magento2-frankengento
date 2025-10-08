<?php

require __DIR__ . '/../app/autoload.php';

use Magento\Framework\Exception\LocalizedException;
use Opengento\Frankengento\App\Application;
use Opengento\Frankengento\ObjectManager\BootstrapPool;

$bootstrapPool = new BootstrapPool();
$handler = static function () use ($bootstrapPool): void {
    try {
        $bootstrap = $bootstrapPool->get();
        $app = $bootstrap->createApplication(Application::class);
        if ($app !== null) {
            $bootstrap->run($app);
        }
    } catch (LocalizedException) {
        exit(1);
    }
};

$maxRequests = (int)($_SERVER['MAX_REQUESTS'] ?? 0);
$nbRequests = 0;
do {
    $keepRunning = \frankenphp_handle_request($handler);
} while ($keepRunning && !$maxRequests && $nbRequests++ < $maxRequests);
