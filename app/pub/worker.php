<?php

require __DIR__ . '/../app/autoload.php';

use Magento\Framework\App\State\ReloadProcessorInterface;
use Opengento\Frankengento\App\Application;
use Opengento\Frankengento\ObjectManager\ObjectManagerFactory;

$objectManagerFactory = new ObjectManagerFactory();
$objectManager = $objectManagerFactory->create('frontend');
/** @var Application $app */
$app = $this->objectManager->create(Application::class, ['objectManager' => $this->objectManager]);

$handler = static function () use ($app): void {
    $response = $app->launch();

    http_response_code($response->getHttpResponseCode());

    foreach ($response->getHeaders()?->toArray() ?? [] as $fieldName => $value) {
        header($fieldName . ': ' . $value);
    }

    echo $response->getBody() ?? '';
};

$maxRequests = (int)($_SERVER['MAX_REQUESTS'] ?? 0);
$nbRequests = 0;
do {
    $keepRunning = \frankenphp_handle_request($handler);
    $reloadProcessor = $this->objectManager->get(ReloadProcessorInterface::class);
    $reloadProcessor->reloadState();
    $this->objectManager->_resetState();
    \gc_collect_cycles();
} while ($keepRunning && !$maxRequests && $nbRequests++ < $maxRequests);
