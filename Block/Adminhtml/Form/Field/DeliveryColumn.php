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
use Magento\Shipping\Model\Config\Source\Allmethods;
use Magento\Store\Model\ScopeInterface;

class DeliveryColumn extends Select
{
    private Allmethods $allmethods;

    /**
     * @param Allmethods $allmethods
     * @param Context    $context
     * @param array      $data
     */
    public function __construct(
        Allmethods $allmethods,
        Context    $context,
        array      $data = []
    ) {
        parent::__construct($context, $data);

        $this->allmethods = $allmethods;
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
        return $this->allmethods->toOptionArray();
    }
}
