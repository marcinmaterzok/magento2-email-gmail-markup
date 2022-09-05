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
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Mtrzk\GmailMarkup\Model\Config;
use Mtrzk\GmailMarkup\Model\Processor\OrderProcessor;

class OrderEmailObserver implements ObserverInterface
{
    private Config $config;
    private OrderProcessor $orderProcessor;

    /**
     * @param Config         $config
     * @param OrderProcessor $orderProcessor
     */
    public function __construct(
        Config         $config,
        OrderProcessor $orderProcessor
    ) {
        $this->orderProcessor = $orderProcessor;
        $this->config         = $config;
    }

    /** {@inheritDoc} */
    public function execute(Observer $observer): void
    {
        $sender = $observer->getSender();

        if (!$sender instanceof OrderSender) {
            return;
        }

        /** @var DataObject $transport */
        $transport = $observer->getData('transportObject');

        /** @var OrderInterface $order */
        $order = $transport->getOrder();

        if (!$order) {
            return;
        }

        $storeId = (int) $order->getStoreId();

        if (!$this->config->isEnabled($storeId) && !$this->config->isOrderEnabled($storeId)) {
            return;
        }

        $jsonData = $this->orderProcessor->processOrder($order);

        $transport->setData('gmailMarkup', $jsonData);
    }
}
