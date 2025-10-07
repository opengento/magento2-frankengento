<?php

/**
 * FrankenWorker
 *
 * @copyright Copyright © 2025 Blackbird Agency. All rights reserved.
 * @author    sebastien@bird.eu
 */

declare(strict_types=1);

namespace Opengento\Frankengento\Model;

use Magento\Framework\ObjectManagerInterface;
use Opengento\Frankengento\ObjectManager\AppObjectManager;
use Opengento\Frankengento\App\Application;
use Magento\Framework\App\State\ReloadProcessorInterface;
use Psr\Log\LoggerInterface;
use Throwable;

class PersistentWorker
{
    private int $requestsProcessed = 0;
    private bool $shouldStop = false;

    public function __construct(
        public readonly int $id,
        private ObjectManagerInterface $objectManager,
        private LoggerInterface $logger,
        private int $maxRequests = 1000
    ) {}

    public function processRequest(array $requestData): array
    {
        if ($this->shouldStop) {
            return ['status' => 'worker_stopping'];
        }

        try {
            $startTime = microtime(true);

            /** @var Application $app */
            $app = $this->objectManager->create(Application::class, ['objectManager' => $this->objectManager]);
            $response = $app->launch();

            $this->requestsProcessed++;

            return [
                'status' => 'success',
                'content' => $response->getContent(),
                'headers' => $response->getHeaders()?->toArray(),
                'http_code' => $response->getHttpResponseCode(),
                'processing_time' => microtime(true) - $startTime,
                'requests_processed' => $this->requestsProcessed,
                'should_restart' => $this->shouldStop
            ];
        } catch (Throwable $e) {
            $this->logger->error("Worker error: " . $e->getMessage());

            return [
                'status' => 'error',
                'message' => $e->getMessage(),
                'should_restart' => true
            ];
        } finally {
            $reloadProcessor = $this->objectManager->get(ReloadProcessorInterface::class);
            $reloadProcessor->reloadState();
            $this->objectManager->_resetState();

            // Vérification d'intégrité de l'ObjectManager
            if ($this->objectManager !== AppObjectManager::getInstance()) {
                $this->logger->error('ObjectManager integrity check failed, forcing restart');
                $this->shouldStop = true;
            } elseif ($this->requestsProcessed >= $this->maxRequests) {
                $this->shouldStop = true;
                $this->logger->info("Worker reached max requests ({$this->maxRequests}), flagging for restart");
            }

            // Nettoyage mémoire
            gc_collect_cycles();
        }
    }
}
