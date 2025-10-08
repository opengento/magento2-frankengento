<?php

declare(strict_types=1);

namespace Opengento\Frankengento\ObjectManager;

use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\ObjectManagerFactory;
use Magento\Framework\App\State\ReloadProcessorInterface;
use Magento\Framework\AppInterface;
use Magento\Framework\ObjectManager\ResetAfterRequestInterface;

use function gc_collect_cycles;

class AppBootstrap extends Bootstrap
{
    /**
     * @inerhitDoc
     */
    public static function create($rootDir, array $initParams, ?ObjectManagerFactory $factory = null): static
    {
        self::populateAutoloader($rootDir, $initParams);

        return new self($factory ?? self::createObjectManagerFactory($rootDir, $initParams), $rootDir, $initParams);
    }

    /**
     * @inerhitDoc
     */
    public static function createObjectManagerFactory($rootDir, array $initParams): AppObjectManagerFactory
    {
        return new AppObjectManagerFactory(
            self::createFilesystemDirectoryList($rootDir, $initParams),
            self::createFilesystemDriverPool($initParams),
            self::createConfigFilePool()
        );
    }

    /**
     * @inerhitDoc
     */
    public function createApplication($type, $arguments = []): ?AppInterface
    {
        $arguments['objectManager'] ??= $this->getObjectManager();

        return parent::createApplication($type, $arguments);
    }

    /**
     * @inerhitDoc
     */
    public function run(AppInterface $application): void
    {
        parent::run($application);
        $objectManager = $this->getObjectManager();
        $reloadProcessor = $objectManager->get(ReloadProcessorInterface::class);
        $reloadProcessor->reloadState();
        if ($objectManager instanceof ResetAfterRequestInterface) {
            $objectManager->_resetState();
        }
        gc_collect_cycles();
    }
}
