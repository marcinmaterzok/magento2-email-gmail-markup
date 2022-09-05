<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Model\Renderer;

use Mtrzk\GmailMarkup\Api\OrderStatusInterface;
use Mtrzk\GmailMarkup\Api\SchemaTypesInterface;
use Mtrzk\GmailMarkup\Model\Config;

class OrderStatus
{
    private Config $config;

    /**
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @param string $orderStatus
     * @param int    $storeId
     *
     * @return string
     */
    public function getSchemaOrder(string $orderStatus, int $storeId): string
    {
        $mapping = $this->config->getOrderStatusMappingArray($storeId);

        if (count($mapping) > 0) {
            foreach ($mapping as $map) {
                if ($map['order_status'] === $orderStatus) {
                    return $map['schema_status'];
                }
            }
        }

        return OrderStatusInterface::PROCESSING;
    }
}
