<?php

declare(strict_types=1);

namespace Opengento\Frankengento\ObjectManager;

use Magento\Framework\App\AreaList;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManager\ConfigLoaderInterface;

use function strtok;
use function trim;

use const BP;

class BootstrapPool
{
    private AppObjectManagerFactory $factory;
    private AreaList $areaList;

    private array $bootstraps = [];

    public function __construct()
    {
        $this->factory = AppBootstrap::createObjectManagerFactory(BP, $_SERVER);
        $this->areaList = $this->factory->create($_SERVER)->get(AreaList::class);
    }

    /**
     * @throws LocalizedException
     */
    public function get(): AppBootstrap
    {
        $areaCode = $this->areaList->getCodeByFrontName(strtok(trim($_SERVER['REQUEST_URI'], '/'), '/'));

        return $this->bootstraps[$areaCode] ??= $this->createBootstrap($areaCode);
    }

    /**
     * @throws LocalizedException
     */
    private function createBootstrap(string $areaCode): AppBootstrap
    {
        $bootstrap = AppBootstrap::create(BP, $_SERVER, $this->factory);
        $globalObjectManager = $bootstrap->getObjectManager();
        $globalObjectManager->configure($globalObjectManager->get(ConfigLoaderInterface::class)->load($areaCode));
        $globalObjectManager->get(State::class)->setAreaCode($areaCode);

        return $bootstrap;
    }
}
