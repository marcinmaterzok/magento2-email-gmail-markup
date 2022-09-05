<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Api;

interface OrderStatusInterface
{
    public const CANCELLED        = 'http://schema.org/OrderCancelled';
    public const DELIVERED        = 'http://schema.org/OrderDelivered';
    public const IN_TRANSIT       = 'http://schema.org/OrderInTransit';
    public const PAYMENT_DUE      = 'http://schema.org/OrderPaymentDue';
    public const PICKUP_AVAILABLE = 'http://schema.org/OrderPickupAvailable';
    public const PROBLEM          = 'http://schema.org/OrderProblem';
    public const PROCESSING       = 'http://schema.org/OrderProcessing';
    public const RETURNED         = 'http://schema.org/OrderReturned';
}
