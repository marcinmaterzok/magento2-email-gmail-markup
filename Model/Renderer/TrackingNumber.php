<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Model\Renderer;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\ShipmentInterface;
use Magento\Shipping\Model\Config\Source\Allmethods;
use Mtrzk\GmailMarkup\Model\Config;

class TrackingNumber
{
    protected Config $config;
    protected Allmethods $allmethods;

    /**
     * @param Config     $config
     * @param Allmethods $allmethods
     */
    public function __construct(
        Config     $config,
        Allmethods $allmethods
    ) {
        $this->config     = $config;
        $this->allmethods = $allmethods;
    }

    /**
     * Here is class with function to make preference if you have some custom tracking Url method
     *
     * @param ShipmentInterface $shipment
     * @param OrderInterface    $order
     *
     * @return null|string
     */
    public function getTrackingUrl(ShipmentInterface $shipment, OrderInterface $order): ?string
    {
        $deliveryMethod     = $order->getShippingMethod(true)->getData();
        $deliveryMethodName = $deliveryMethod['carrier_code'] . "_" . $deliveryMethod['method'];
        $storeId            = (int) $order->getStoreId();
        $mapping            = $this->config->getShipmentTrackingMappingArray($storeId);

        if (count($mapping) > 0) {
            foreach ($mapping as $map) {
                if ($map['delivery_method'] === $deliveryMethodName) {
                    return str_replace(
                        [
                            "{{shipment_id}}",
                            "{{tracking_number}}"
                        ],
                        [
                            $shipment->getEntityId(),
                            $this->getTrackingNumber($shipment)
                        ],
                        $map['tracking_url']
                    );
                }
            }
        }

        return null;
    }

    /**
     * @param ShipmentInterface $shipment
     *
     * @return string
     */
    public function getTrackingNumber(ShipmentInterface $shipment): string
    {
        return current($shipment->getTracks())->getTrackNumber();
    }
}
