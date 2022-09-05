<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Model\Processor;

use InvalidArgumentException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Url;
use Magento\Framework\UrlInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Store\Model\StoreManagerInterface;
use Mtrzk\GmailMarkup\Model\Config;
use Mtrzk\GmailMarkup\Model\Config\Source\UrlType;
use Mtrzk\GmailMarkup\Model\EmailLookup;
use Mtrzk\GmailMarkup\Model\Renderer\OrderStatus;
use Psr\Log\LoggerInterface;

abstract class AbstractProcessor
{
    protected const SCHEMA_URL = 'http://schema.org/';

    protected Config $config;
    protected EmailLookup $emailLookup;
    protected SerializerInterface $serializer;
    protected Url $url;
    protected OrderStatus $orderStatus;
    protected LoggerInterface $logger;
    protected StoreManagerInterface $storeManager;

    /**
     * @param Config                $config
     * @param EmailLookup           $emailLookup
     * @param SerializerInterface   $serializer
     * @param Url                   $url
     * @param OrderStatus           $orderStatus
     * @param LoggerInterface       $logger
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Config                $config,
        EmailLookup           $emailLookup,
        SerializerInterface   $serializer,
        Url                   $url,
        OrderStatus           $orderStatus,
        LoggerInterface       $logger,
        StoreManagerInterface $storeManager
    ) {
        $this->config       = $config;
        $this->emailLookup  = $emailLookup;
        $this->serializer   = $serializer;
        $this->url          = $url;
        $this->orderStatus  = $orderStatus;
        $this->logger       = $logger;
        $this->storeManager = $storeManager;
    }

    /**
     * @param string $email
     * @param int    $storeId
     *
     * @return bool
     */
    protected function isEmailEnabledToSend(string $email, int $storeId): bool
    {
        return $this->emailLookup->checkEmail($email, $storeId);
    }

    /**
     * @param array $array
     *
     * @return string
     */
    private function toJson(array $array): string
    {


        try {
            return (string) $this->serializer->serialize($array);
        } catch (InvalidArgumentException $e) {
            return "";
        }
    }

    /**
     * @param array $array
     *
     * @return string
     */
    protected function generateScript(array $array): string
    {
        $html = '<script type="application/ld+json">';
        $html .= $this->toJson($array);
        $html .= '</script>';

        return $html;
    }

    /**
     * @param string $schema
     *
     * @return string
     */
    protected function getSchemaUrl(string $schema): string
    {
        return self::SCHEMA_URL . $schema;
    }

    /**
     * @param OrderInterface $order
     *
     * @return string
     */
    protected function getOrderViewUrl(OrderInterface $order): string
    {
        $storeId = (int) $order->getStoreId();

        if ($this->config->getOrderViewActionType($storeId) === UrlType::CUSTOM_TYPE) {
            return str_replace(
                [
                    "{{order_id}}",
                    "{{increment_id}}"
                ],
                [
                    $order->getEntityId(),
                    $order->getIncrementId()
                ],
                $this->config->getOrderViewActionCustomUrl($storeId)
            );
        }

        return $this->localhostFixUrl(
            $this->url->getUrl('sales/order/view', [
                '_scope' => $storeId,
                'id'     => $order->getEntityId(),
                '_nosid' => true
            ])
        );
    }

    /**
     * @param int $storeId
     *
     * @return string
     * @throws NoSuchEntityException
     */
    protected function getMediaUrl(int $storeId): string
    {
        return $this->localhostFixUrl($this->storeManager->getStore($storeId)->getBaseUrl(UrlInterface::URL_TYPE_MEDIA));
    }

    /**
     * Google if url is localhost show error, function change http://localhost to http://example.com/
     *
     * @param string|null $url
     *
     * @return string
     */
    protected function localhostFixUrl(?string $url): ?string
    {
        return str_replace("localhost", "example.com", $url);
    }
}
