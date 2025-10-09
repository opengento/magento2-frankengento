<?php

declare(strict_types=1);

namespace Opengento\Frankengento\App;

use Magento\Framework\App\State\ReloadProcessorInterface;

class ReloadProcessor implements ReloadProcessorInterface
{
    /**
     * @inerhitDoc
     */
    public function reloadState(): void
    {
        // ToDo: we need to investigate this test in order to reset any missing state not handled natively by the framework:
        // ToDo: @see vendor/magento/magento2-base/dev/tests/integration/framework/Magento/TestFramework/ApplicationStateComparator
    }
}
