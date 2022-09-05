<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Api\Json;

interface ProductInterface
{
    public const NAME        = 'name';
    public const URL         = 'url';
    public const IMAGE       = 'image';
    public const SKU         = 'sku';
    public const DESCRIPTION = 'description';
    public const BRAND       = 'brand';
}
