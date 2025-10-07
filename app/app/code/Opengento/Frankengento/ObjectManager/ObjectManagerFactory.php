<?php

declare(strict_types=1);

namespace Opengento\Frankengento\ObjectManager;

use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManager\ConfigLoaderInterface;
use Magento\Framework\ObjectManagerInterface;

use const BP;

class ObjectManagerFactory
{
    /**
     * @throws LocalizedException
     */
    public function create(string $areaCode): ObjectManagerInterface
    {
        $bootstrap = AppBootstrap::create(BP, $_SERVER);
        $globalObjectManager = $bootstrap->getObjectManager();
        $globalObjectManager->configure($globalObjectManager->get(ConfigLoaderInterface::class)->load($areaCode));
        $globalObjectManager->get(State::class)->setAreaCode($areaCode);

        return $globalObjectManager;
    }
}
