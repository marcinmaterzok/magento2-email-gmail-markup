<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Model\Processor;

use Exception;
use Magento\Sales\Api\Data\OrderInterface;
use Mtrzk\GmailMarkup\Api\Json\BaseInterface;
use Mtrzk\GmailMarkup\Api\Json\ProductInterface;
use Mtrzk\GmailMarkup\Api\Json\OrderInterface as JsonOrderInterface;
use Mtrzk\GmailMarkup\Model\Config\Source\UrlType;

class OrderProcessor extends AbstractProcessor
{
    /**
     * @see https://developers.google.com/gmail/markup/reference/order
     *
     * @param OrderInterface $order
     *
     * @return string
     */
    public function processOrder(OrderInterface $order): string
    {
        try {
            $email   = $order->getCustomerEmail();
            $storeId = (int) $order->getStoreId();

            if (!$this->isEmailEnabledToSend($email, $storeId)) {
                return "";
            }

            $currencyCode = $order->getOrderCurrencyCode();

            $arrayJson = [
                BaseInterface::CONTEXT             => BaseInterface::SCHEMA_HTTP,
                BaseInterface::TYPE                => BaseInterface::TYPE_ORDER,
                BaseInterface::MERCHANT            => [
                    BaseInterface::TYPE => BaseInterface::TYPE_ORGANIZATION,
                    BaseInterface::NAME => $this->config->getMerchantName($storeId)
                ],
                JsonOrderInterface::ORDER_NUMBER   => $order->getIncrementId(),
                JsonOrderInterface::PRICE_CURRENCY => $currencyCode,
                JsonOrderInterface::PRICE          => number_format((float) $order->getGrandTotal(), 2),
                JsonOrderInterface::ORDER_DATE     => $order->getCreatedAt(),
                JsonOrderInterface::ORDER_STATUS   => $this->orderStatus->getSchemaOrder($order->getState(), $storeId),
            ];

            if ($this->config->isOrderAddViewActon($storeId) && !$order->getCustomerIsGuest()) {
                $url = $this->getOrderViewUrl($order);

                $arrayJson[BaseInterface::POTENTIAL_ACTION] = [
                    BaseInterface::TYPE      => BaseInterface::TYPE_VIEW_ACTION,
                    JsonOrderInterface::URL  => $url,
                    JsonOrderInterface::NAME => $this->config->getOrderViewActionLabel($storeId)
                ];

                $arrayJson[BaseInterface::URL] = $url;
            }

            $productsJson = [];

            foreach ($order->getAllVisibleItems() as $item) {
                $productImage = $this->getMediaUrl($storeId) . $item->getProduct()->getImage();
                $itemJson = [
                    BaseInterface::TYPE                   => BaseInterface::TYPE_OFFER,
                    JsonOrderInterface::ITEM_OFFERED      => [
                        BaseInterface::TYPE     => BaseInterface::TYPE_PRODUCT,
                        ProductInterface::NAME  => $item->getName(),
                        ProductInterface::SKU   => $item->getProduct()->getSku(),
                        ProductInterface::URL   => $this->localhostFixUrl($item->getProduct()->getProductUrl()),
                        ProductInterface::IMAGE => $productImage,
                    ],
                    JsonOrderInterface::PRICE             => number_format((float) $item->getPriceInclTax(), 2),
                    JsonOrderInterface::PRICE_CURRENCY    => $currencyCode,
                    JsonOrderInterface::ELIGIBLE_QUANTITY => [
                        BaseInterface::TYPE       => BaseInterface::TYPE_QUANTITATIVE_VALUE,
                        JsonOrderInterface::VALUE => (int) $item->getQtyOrdered()
                    ]
                ];

                $productsJson[] = $itemJson;
            }

            if ($order->getDiscountAmount() > 0) {
                $arrayJson[JsonOrderInterface::DISCOUNT]          = $order->getDiscountAmount();
                $arrayJson[JsonOrderInterface::DISCOUNT_CURRENCY] = $currencyCode;
            }

            $arrayJson[JsonOrderInterface::ACCEPTED_OFFER] = $productsJson;

            if ($this->config->isOrderAddBillingData($storeId)) {
                $billingAddress = $order->getBillingAddress();
                $companyName    = $billingAddress->getCompany();

                if (!empty($companyName)) {
                    $customerData = [
                        BaseInterface::TYPE => BaseInterface::TYPE_ORGANIZATION,
                        BaseInterface::NAME => $companyName
                    ];
                } else {
                    $customerName = $billingAddress->getFirstname() . " " . $billingAddress->getLastname();
                    $customerData = [
                        BaseInterface::TYPE => BaseInterface::TYPE_PERSON,
                        BaseInterface::NAME => $customerName
                    ];
                    $companyName  = $customerName;
                }

                $billingData = [
                    JsonOrderInterface::CUSTOMER        => $customerData,
                    JsonOrderInterface::BILLING_ADDRESS => [
                        BaseInterface::TYPE                  => BaseInterface::TYPE_POSTAL_ADDRESS,
                        BaseInterface::NAME                  => $companyName,
                        JsonOrderInterface::STREET_ADDRESS   => implode(" ", $billingAddress->getStreet()),
                        JsonOrderInterface::ADDRESS_REGION   => $billingAddress->getRegion(),
                        JsonOrderInterface::ADDRESS_LOCALITY => $billingAddress->getCity(),
                        JsonOrderInterface::ADDRESS_COUNTRY  => $billingAddress->getCountryId(),
                    ]
                ];

                $arrayJson = array_merge($arrayJson, $billingData);
            }

            return $this->generateScript($arrayJson);

        } catch (Exception $e) {
            $this->logger->error("OrderProcessor error on Increment ID ".$order->getIncrementId().": ".$e->getMessage());

            return "";
        }
    }
}
