<?php

declare(strict_types=1);

namespace Opengento\Frankengento\ObjectManager;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManager\ConfigInterface;
use Magento\Framework\ObjectManager\FactoryInterface;
use Magento\Framework\ObjectManager\ResetAfterRequestInterface;
use Magento\Framework\ObjectManager\Resetter\Resetter;
use Magento\Framework\ObjectManager\Resetter\ResetterInterface;
use ReflectionException;

class AppObjectManager extends ObjectManager implements ResetAfterRequestInterface
{
    private ResetterInterface $resetter;

    public function __construct(
        FactoryInterface $factory,
        ConfigInterface $config,
        array &$sharedInstances = []
    ) {
        $this->resetter = new Resetter();
        parent::__construct($factory, $config, $sharedInstances);
        $this->resetter->setObjectManager($this);
    }

    /**
     * @ingeritdoc
     * @throws ReflectionException
     */
    public function _resetState(): void
    {
        $this->resetter->_resetState();
    }

    /**
     * @ingeritdoc
     */
    public function create($type, array $arguments = [])
    {
        $object = parent::create($type, $arguments);
        $this->resetter->addInstance($object);

        return $object;
    }
}
