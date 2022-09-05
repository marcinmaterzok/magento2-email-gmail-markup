<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Observer;

use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\ShipmentInterface;
use Magento\Sales\Model\Order\Shipment\Sender\EmailSender;
use Magento\Sales\Model\Order\Email\Sender\ShipmentSender;
use Mtrzk\GmailMarkup\Model\Config;
use Mtrzk\GmailMarkup\Model\Processor\ShipmentProcessor;

class ShipmentEmailObserver implements ObserverInterface
{
    private Config $config;
    private ShipmentProcessor $shipmentProcessor;

    /**
     * @param Config            $config
     * @param ShipmentProcessor $shipmentProcessor
     */
    public function __construct(
        Config            $config,
        ShipmentProcessor $shipmentProcessor
    ) {
        $this->shipmentProcessor = $shipmentProcessor;
        $this->config            = $config;
    }

    /** {@inheritDoc} */
    public function execute(Observer $observer): void
    {
        $sender = $observer->getSender();

        if (!$sender instanceof EmailSender && !$sender instanceof ShipmentSender) {
            return;
        }

        /** @var DataObject $transport */
        $transport = $observer->getData('transportObject');

        /** @var OrderInterface $order */
        $order = $transport->getOrder();

        /** @var ShipmentInterface $shipment */
        $shipment = $transport->getShipment();

        if (!$order || !$shipment) {
            return;
        }

        $storeId = (int) $order->getStoreId();

        if (!$this->config->isEnabled($storeId) && !$this->config->isShipmentEnabled($storeId)) {
            return;
        }

        $jsonData = $this->shipmentProcessor->processShipment($shipment, $order);

        $transport->setData('gmailMarkup', $jsonData);
    }
}
