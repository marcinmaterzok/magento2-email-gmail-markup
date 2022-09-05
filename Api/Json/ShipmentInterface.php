<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Api\Json;

interface ShipmentInterface
{
    public const ORDER_NUMBER           = 'orderNumber';
    public const ORDER_STATUS           = 'orderStatus';
    public const ITEM_SHIPPED           = 'itemShipped';
    public const TRACKING_NUMBER        = 'trackingNumber';
    public const TRACKING_URL           = 'trackingUrl';
    public const URL                    = 'url';
    public const EXPECTED_ARRIVAL_UNTIL = 'expectedArrivalUntil';
    public const CARRIER                = 'carrier';
    public const VALUE                  = 'value';
    public const PART_OF_ORDER          = 'partOfOrder';
    public const DELIVERY_ADDRESS       = 'deliveryAddress';
    public const NAME                   = 'name';
    public const STREET_ADDRESS         = 'streetAddress';
    public const ADDRESS_LOCALITY       = 'addressLocality';
    public const ADDRESS_REGION         = 'addressRegion';
    public const ADDRESS_COUNTRY        = 'addressCountry';
    public const POSTAL_CODE            = 'postalCode';
}
