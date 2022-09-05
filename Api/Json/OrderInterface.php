<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Api\Json;

interface OrderInterface
{
    public const ORDER_NUMBER      = 'orderNumber';
    public const ORDER_STATUS      = 'orderStatus';
    public const PRICE_CURRENCY    = 'priceCurrency';
    public const PRICE             = 'price';
    public const ACCEPTED_OFFER    = 'acceptedOffer';
    public const ITEM_OFFERED      = 'itemOffered';
    public const NAME              = 'name';
    public const ELIGIBLE_QUANTITY = 'eligibleQuantity';
    public const VALUE             = 'value';
    public const URL               = 'url';
    public const ORDER_DATE        = 'orderDate';
    public const BILLING_ADDRESS   = 'billingAddress';
    public const STREET_ADDRESS    = 'streetAddress';
    public const ADDRESS_LOCALITY  = 'addressLocality';
    public const ADDRESS_REGION    = 'addressRegion';
    public const ADDRESS_COUNTRY   = 'addressCountry';
    public const PAYMENT_METHOD    = 'paymentMethod';
    public const CUSTOMER          = 'customer';
    public const DISCOUNT          = 'discount';
    public const DISCOUNT_CURRENCY = 'discountCurrency';
}
