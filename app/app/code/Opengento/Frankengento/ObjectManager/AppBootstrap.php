<?php

declare(strict_types=1);

namespace Opengento\Frankengento\ObjectManager;

use Magento\Framework\App\Bootstrap;
use Magento\Framework\App\ObjectManagerFactory;

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
}
