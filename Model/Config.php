<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Model;

use InvalidArgumentException;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private const XML_GENERAL_IS_ENABLED          = 'mtrzk_gmail_markup/general/is_enabled';
    private const XML_GENERAL_IS_ORDER_ENABLED    = 'mtrzk_gmail_markup/general/is_order_enabled';
    private const XML_GENERAL_IS_SHIPMENT_ENABLED = 'mtrzk_gmail_markup/general/is_shipment_enabled';
    private const XML_GENERAL_MERCHANT_NAME       = 'mtrzk_gmail_markup/general/merchant_name';

    private const XML_ORDER_ADD_BILLING_DATA                 = 'mtrzk_gmail_markup/order_settings/add_billing_data';
    private const XML_ORDER_ORDER_STATUS_MAPPING             = 'mtrzk_gmail_markup/order_settings/order_status_mapping';
    private const XML_ORDER_ADD_ACTION_FOR_CUSTOMER          = 'mtrzk_gmail_markup/order_settings/add_action_for_customer';
    private const XML_ORDER_CUSTOMER_VIEW_ACTION_LABEL       = 'mtrzk_gmail_markup/order_settings/customer_view_action_label';
    private const XML_ORDER_CUSTOMER_VIEW_ACTION_DESCRIPTION = 'mtrzk_gmail_markup/order_settings/customer_view_action_description';
    private const XML_ORDER_CUSTOMER_VIEW_ACTION_TYPE        = 'mtrzk_gmail_markup/order_settings/customer_view_action_type';
    private const XML_ORDER_CUSTOMER_VIEW_ACTION_CUSTOM_URL  = 'mtrzk_gmail_markup/order_settings/customer_view_action_custom_url';

    private const XML_SHIPMENT_ADD_TRACK_ACTION          = 'mtrzk_gmail_markup/shipment_settings/add_track_action';
    private const XML_SHIPMENT_SHIPMENT_DELIVERY_MAPPING = 'mtrzk_gmail_markup/shipment_settings/shipment_delivery_mapping';
    private const XML_SHIPMENT_SHIPMENT_TRACKING_MAPPING = 'mtrzk_gmail_markup/shipment_settings/shipment_tracking_mapping';

    private const XML_ADVANCED_ADD_MARKUP_TO_EMAILS = 'mtrzk_gmail_markup/advanced_settings/add_markup_to_emails';

    private ScopeConfigInterface $scopeConfig;
    private SerializerInterface $serializer;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param SerializerInterface  $serializer
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        SerializerInterface  $serializer
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->serializer  = $serializer;
    }

    /**
     * @param int $storeId
     *
     * @return bool
     */
    public function isEnabled(int $storeId = 0): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_GENERAL_IS_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     *
     * @return bool
     */
    public function isOrderEnabled(int $storeId = 0): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_GENERAL_IS_ORDER_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     *
     * @return bool
     */
    public function isShipmentEnabled(int $storeId = 0): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_GENERAL_IS_SHIPMENT_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     *
     * @return string
     */
    public function getMerchantName(int $storeId = 0): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_GENERAL_MERCHANT_NAME,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     *
     * @return bool
     */
    public function isOrderAddBillingData(int $storeId = 0): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_ORDER_ADD_BILLING_DATA,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     *
     * @return string
     */
    public function getOrderStatusMapping(int $storeId = 0): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_ORDER_ORDER_STATUS_MAPPING,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     *
     * @return array
     */
    public function getOrderStatusMappingArray(int $storeId = 0): array
    {
        try {
            return (array) $this->serializer->unserialize(
                $this->getOrderStatusMapping($storeId)
            );
        } catch (InvalidArgumentException $e) {
            return [];
        }
    }

    /**
     * @param int $storeId
     *
     * @return bool
     */
    public function isOrderAddViewActon(int $storeId = 0): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_ORDER_ADD_ACTION_FOR_CUSTOMER,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     *
     * @return string
     */
    public function getOrderViewActionLabel(int $storeId = 0): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_ORDER_CUSTOMER_VIEW_ACTION_LABEL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     *
     * @return string
     */
    public function getOrderViewActionDescription(int $storeId = 0): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_ORDER_CUSTOMER_VIEW_ACTION_DESCRIPTION,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     *
     * @return string
     */
    public function getOrderViewActionType(int $storeId = 0): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_ORDER_CUSTOMER_VIEW_ACTION_TYPE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     *
     * @return string
     */
    public function getOrderViewActionCustomUrl(int $storeId = 0): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_ORDER_CUSTOMER_VIEW_ACTION_CUSTOM_URL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int $storeId
     *
     * @return bool
     */
    public function isShipmentAddViewActon(int $storeId = 0): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_SHIPMENT_ADD_TRACK_ACTION,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     *
     * @return string
     */
    public function getShipmentDeliveryMapping(int $storeId = 0): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_SHIPMENT_SHIPMENT_DELIVERY_MAPPING,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     *
     * @return array
     */
    public function getShipmentDeliveryMappingArray(int $storeId = 0): array
    {
        try {
            return (array) $this->serializer->unserialize(
                $this->getShipmentDeliveryMapping($storeId)
            );
        } catch (InvalidArgumentException $e) {
            return [];
        }
    }

    /**
     * @param int|null $storeId
     *
     * @return string
     */
    public function getShipmentTrackingMapping(int $storeId = 0): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_SHIPMENT_SHIPMENT_TRACKING_MAPPING,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * @param int|null $storeId
     *
     * @return array
     */
    public function getShipmentTrackingMappingArray(int $storeId = 0): array
    {
        try {
            return (array) $this->serializer->unserialize(
                $this->getShipmentTrackingMapping($storeId)
            );
        } catch (InvalidArgumentException $e) {
            return [];
        }
    }

    /**
     * @param int|null $storeId
     *
     * @return string
     */
    public function getAddMarkupEmailSettings(int $storeId = 0): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_ADVANCED_ADD_MARKUP_TO_EMAILS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
