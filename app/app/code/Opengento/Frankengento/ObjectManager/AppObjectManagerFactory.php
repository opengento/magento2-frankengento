<?php

declare(strict_types=1);

namespace Opengento\Frankengento\ObjectManager;

use Magento\Framework\App\ObjectManagerFactory;

class AppObjectManagerFactory extends ObjectManagerFactory
{
    protected $_locatorClassName = AppObjectManager::class;
}
