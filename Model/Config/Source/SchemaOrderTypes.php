<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;
use Mtrzk\GmailMarkup\Api\OrderStatusInterface;

class SchemaOrderTypes implements ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = [];

        foreach ($this->toArray() as $value => $label) {
            $optionArray[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $optionArray;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            OrderStatusInterface::CANCELLED        => OrderStatusInterface::CANCELLED,
            OrderStatusInterface::DELIVERED        => OrderStatusInterface::DELIVERED,
            OrderStatusInterface::IN_TRANSIT       => OrderStatusInterface::IN_TRANSIT,
            OrderStatusInterface::PAYMENT_DUE      => OrderStatusInterface::PAYMENT_DUE,
            OrderStatusInterface::PICKUP_AVAILABLE => OrderStatusInterface::PICKUP_AVAILABLE,
            OrderStatusInterface::PROBLEM          => OrderStatusInterface::PROBLEM,
            OrderStatusInterface::PROCESSING       => OrderStatusInterface::PROCESSING,
            OrderStatusInterface::RETURNED         => OrderStatusInterface::RETURNED
        ];
    }
}

