<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Model\Processor;

use Exception;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Url;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\ShipmentInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mtrzk\GmailMarkup\Api\Json\BaseInterface;
use Mtrzk\GmailMarkup\Api\Json\OrderInterface as JsonOrderInterface;
use Mtrzk\GmailMarkup\Api\Json\ProductInterface;
use Mtrzk\GmailMarkup\Api\Json\ShipmentInterface as JsonShipmentInterface;
use Mtrzk\GmailMarkup\Model\Config;
use Mtrzk\GmailMarkup\Model\EmailLookup;
use Mtrzk\GmailMarkup\Model\Renderer\DeliveryName;
use Mtrzk\GmailMarkup\Model\Renderer\OrderStatus;
use Mtrzk\GmailMarkup\Model\Renderer\TrackingNumber;
use Psr\Log\LoggerInterface;

class ShipmentProcessor extends AbstractProcessor
{
    protected TrackingNumber $trackingNumber;
    protected DeliveryName $deliveryName;

    /**
     * @param Config                $config
     * @param EmailLookup           $emailLookup
     * @param SerializerInterface   $serializer
     * @param Url                   $url
     * @param OrderStatus           $orderStatus
     * @param TrackingNumber        $trackingNumber
     * @param DeliveryName          $deliveryName
     * @param LoggerInterface       $logger
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Config                $config,
        EmailLookup           $emailLookup,
        SerializerInterface   $serializer,
        Url                   $url,
        OrderStatus           $orderStatus,
        TrackingNumber        $trackingNumber,
        DeliveryName          $deliveryName,
        LoggerInterface       $logger,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct(
            $config,
            $emailLookup,
            $serializer,
            $url,
            $orderStatus,
            $logger,
            $storeManager
        );

        $this->trackingNumber = $trackingNumber;
        $this->deliveryName   = $deliveryName;
    }

    /**
     * @see https://developers.google.com/gmail/markup/reference/parcel-delivery
     *
     * @param ShipmentInterface $shipment
     * @param OrderInterface    $order
     *
     * @return string
     */
    public function processShipment(ShipmentInterface $shipment, OrderInterface $order): string
    {
        try {
            $email   = $order->getCustomerEmail();
            $storeId = (int) $order->getStoreId();

            if (!$this->isEmailEnabledToSend($email, $storeId)) {
                return "";
            }

            $deliveryMethod = $order->getShippingMethod(true)->getData();
            $deliveryMethodName = $deliveryMethod['carrier_code']."_".$deliveryMethod['method'];
            $shippingAddress = $order->getShippingAddress();
            $shippingName    = $shippingAddress->getCompany();

            if (empty($shippingName)) {
                $shippingName = $shippingAddress->getFirstname() . " " . $shippingAddress->getLastname();
            }

            $arrayJson = [
                BaseInterface::CONTEXT                  => BaseInterface::SCHEMA_HTTP,
                BaseInterface::TYPE                     => BaseInterface::TYPE_PARCEL_DELIVERY,
                JsonShipmentInterface::CARRIER          => [
                    BaseInterface::TYPE => BaseInterface::TYPE_ORGANIZATION,
                    BaseInterface::NAME => $this->deliveryName->getDeliveryName($deliveryMethodName, $storeId)
                ],
                JsonShipmentInterface::DELIVERY_ADDRESS => [
                    BaseInterface::TYPE                     => BaseInterface::TYPE_POSTAL_ADDRESS,
                    BaseInterface::NAME                     => $shippingName,
                    JsonShipmentInterface::STREET_ADDRESS   => implode(" ", $shippingAddress->getStreet()),
                    JsonShipmentInterface::ADDRESS_REGION   => $shippingAddress->getRegion(),
                    JsonShipmentInterface::ADDRESS_LOCALITY => $shippingAddress->getCity(),
                    JsonShipmentInterface::ADDRESS_COUNTRY  => $shippingAddress->getCountryId(),
                    JsonShipmentInterface::POSTAL_CODE      => $shippingAddress->getPostcode()
                ],
                JsonShipmentInterface::PART_OF_ORDER    => [
                    BaseInterface::TYPE              => BaseInterface::TYPE_ORDER,
                    JsonOrderInterface::ORDER_NUMBER => $order->getIncrementId(),
                    BaseInterface::MERCHANT          => [
                        BaseInterface::TYPE => BaseInterface::TYPE_ORGANIZATION,
                        BaseInterface::NAME => $this->config->getMerchantName($storeId)
                    ],
                ],
                JsonShipmentInterface::TRACKING_NUMBER  => $this->trackingNumber->getTrackingNumber($shipment),
                JsonShipmentInterface::ORDER_STATUS     => $this->orderStatus->getSchemaOrder($order->getState(), $storeId),
            ];

            if ($this->config->isShipmentAddViewActon($storeId)) {
                $trackingUrl = $this->trackingNumber->getTrackingUrl($shipment, $order);

                if (!$trackingUrl) {
                    $trackingUrl = $this->getOrderViewUrl($order);
                }

                $arrayJson[BaseInterface::POTENTIAL_ACTION] = [
                    BaseInterface::TYPE        => BaseInterface::TYPE_TRACK_ACTION,
                    JsonShipmentInterface::URL => $trackingUrl
                ];

                $arrayJson[JsonShipmentInterface::TRACKING_URL] = $trackingUrl;
            }

            $productsJson = [];

            foreach ($order->getAllVisibleItems() as $item) {
                $productImage = $this->getMediaUrl($storeId) . $item->getProduct()->getImage();

                $itemJson = [
                    BaseInterface::TYPE     => BaseInterface::TYPE_PRODUCT,
                    ProductInterface::NAME  => $item->getName(),
                    ProductInterface::SKU   => $item->getProduct()->getSku(),
                    ProductInterface::URL   => $this->localhostFixUrl($item->getProduct()->getProductUrl()),
                    ProductInterface::IMAGE => $productImage
                ];

                $productsJson[] = $itemJson;
            }

            $arrayJson[JsonShipmentInterface::ITEM_SHIPPED] = $productsJson;

            $arrayJson[JsonShipmentInterface::EXPECTED_ARRIVAL_UNTIL] = Date('y:m:d', strtotime('+3 days'));

            return $this->generateScript($arrayJson);
        } catch (Exception $e) {
            $this->logger->error("OrderProcessor error on Increment ID " . $order->getIncrementId() . ": " . $e->getMessage());

            return "";
        }
    }
}
