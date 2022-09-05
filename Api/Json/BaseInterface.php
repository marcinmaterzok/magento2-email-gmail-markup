<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Api\Json;

interface BaseInterface
{
    public const CONTEXT          = '@context';
    public const TYPE             = '@type';
    public const MERCHANT         = 'merchant';
    public const SCHEMA_HTTP      = 'http://schema.org';
    public const NAME             = 'name';
    public const POTENTIAL_ACTION = 'potentialAction';
    public const URL              = 'url';

    public const TYPE_PERSON             = 'Person';
    public const TYPE_PAYMENT_METHOD     = 'PaymentMethod';
    public const TYPE_VIEW_ACTION        = 'ViewAction';
    public const TYPE_POSTAL_ADDRESS     = 'PostalAddress';
    public const TYPE_QUANTITATIVE_VALUE = 'QuantitativeValue';
    public const TYPE_PRODUCT            = 'Product';
    public const TYPE_OFFER              = 'Offer';
    public const TYPE_ORDER              = 'Order';
    public const TYPE_ORGANIZATION       = 'Organization';
    public const TYPE_TRACK_ACTION       = 'TrackAction';
    public const TYPE_PARCEL_SERVICE     = 'ParcelService';
    public const TYPE_PARCEL_DELIVERY    = 'ParcelDelivery';
}
