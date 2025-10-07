<?php

/**
 * WorkerManager
 *
 * @copyright Copyright © 2025 Blackbird Agency. All rights reserved.
 * @author    sebastien@bird.eu
 */

declare(strict_types=1);

namespace Opengento\Frankengento\Model;

use Exception;
use Magento\Framework\Exception\LocalizedException;
use Opengento\Frankengento\ObjectManager\ObjectManagerFactory;
use Psr\Log\LoggerInterface;

use function array_shift;

class WorkerManager
{
    private array $freeWorkers = [];
    private int $maxWorkers;
    private int $requestsPerWorker;
    private int $lastId = 0;

    public function __construct(
        private ObjectManagerFactory $objectManagerFactory,
        private LoggerInterface $logger,
        int $maxWorkers = 4,
        int $requestsPerWorker = 1000
    ) {
        $this->maxWorkers = $maxWorkers;
        $this->requestsPerWorker = $requestsPerWorker;
    }

    /**
     * @throws LocalizedException
     */
    public function handleRequest(array $requestData): array
    {
        $areaCode = 'frontend';//ToDo resolve from request data
        $worker = $this->selectWorker($areaCode);
        $response = $worker->processRequest($requestData);
        $this->freeWorker($areaCode, $worker);

        return $response;
    }

    /**
     * @throws LocalizedException
     */
    private function selectWorker(string $areaCode): PersistentWorker
    {
        $worker = array_shift($this->freeWorkers[$areaCode]);
        if ($worker === null) {
            if ($this->lastId >= $this->maxWorkers) {
                throw new Exception('No available workers'); // Custom exception here
            }
            $worker = $this->createWorker($areaCode);
        }

        return $worker;
    }

    private function freeWorker(string $areaCode, PersistentWorker $worker): void
    {
        $this->freeWorkers[$areaCode][] = $worker;
    }

    /**
     * @throws LocalizedException
     */
    private function createWorker(string $areaCode): PersistentWorker
    {
        return new PersistentWorker(
            $this->lastId++,
            $this->objectManagerFactory->create($areaCode),
            $this->logger,
            $this->requestsPerWorker
        );
    }
}
