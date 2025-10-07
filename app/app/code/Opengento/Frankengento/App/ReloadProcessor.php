<?php

declare(strict_types=1);

namespace Opengento\Frankengento\App;

use Magento\Eav\Model\Config;
use Magento\Framework\App\State\ReloadProcessorInterface;
use Magento\Framework\GraphQl\Config\Data;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Search\Request\Config as SearchRequestConfig;

class ReloadProcessor implements ReloadProcessorInterface
{
    public function __construct(
        private SearchRequestConfig $searchRequestConfig,
        private Config $config,
        private ObjectManagerInterface $objectManager
    ) {}

    /**
     * @inerhitDoc
     */
    public function reloadState(): void
    {
        // phpstan:ignore "Class Magento\Framework\GraphQl\Config\Data not found."
        $this->objectManager->get(Data::class)->reinitData();
        $this->searchRequestConfig->reinitData();
        //ToDo look for graphql schema generator?
        $this->config->clearWithoutCleaningCache();
    }
}
