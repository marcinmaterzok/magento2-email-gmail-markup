<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Model\Renderer;

use Magento\Shipping\Model\Config\Source\Allmethods;
use Mtrzk\GmailMarkup\Model\Config;

class DeliveryName
{
    protected Config $config;
    protected Allmethods $allmethods;

    /**
     * @param Config     $config
     * @param Allmethods $allmethods
     */
    public function __construct(
        Config $config,
        Allmethods $allmethods
    ) {
        $this->config = $config;
        $this->allmethods = $allmethods;
    }

    /**
     * @param string $deliveryCode
     * @param int    $storeId
     *
     * @return string
     */
    public function getDeliveryName(string $deliveryCode, int $storeId): string
    {
        $mapping = $this->config->getShipmentDeliveryMappingArray($storeId);

        if (count($mapping) > 0) {
            foreach ($mapping as $map) {
                if ($map['delivery_method'] === $deliveryCode) {
                    return $map['delivery_name'];
                }
            }
        }

        foreach ($this->allmethods->toOptionArray() as $map) {
            if ($map['value'] === $deliveryCode) {
                return $map['label'];
            }
        }

        return '';
    }
}
