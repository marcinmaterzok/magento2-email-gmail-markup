<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Block\Adminhtml\Form\Field;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;
use Magento\Store\Model\ScopeInterface;

use Magento\Sales\Model\ResourceModel\Order\Status\Collection as OrderStatusCollection;

class OrderMagentoColumn extends Select
{
    private OrderStatusCollection $orderStatusCollection;

    /**
     * @param OrderStatusCollection $orderStatusCollection
     * @param Context               $context
     * @param array                 $data
     */
    public function __construct(
        OrderStatusCollection $orderStatusCollection,
        Context               $context,
        array                 $data = []
    ) {
        parent::__construct($context, $data);

        $this->orderStatusCollection = $orderStatusCollection;
    }

    /**
     * @param string $value
     *
     * @return $this
     */
    public function setInputName($value)
    {
        /* @phpstan-ignore-next-line */
        return $this->setName($value);
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setInputId($value)
    {
        return $this->setId($value);
    }

    /**
     * @return string
     */
    public function _toHtml(): string
    {
        if (!$this->getOptions()) {
            $this->setOptions($this->getSourceOptions());
        }

        return parent::_toHtml();
    }

    /**
     * @return array
     */
    private function getSourceOptions(): array
    {
        return $this->orderStatusCollection->toOptionArray();
    }
}
