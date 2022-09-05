<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class UrlType implements ArrayInterface
{
    public const DEFAULT_TYPE = 'default';
    public const CUSTOM_TYPE  = 'custom';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = [];

        foreach ($this->toArray() as $value => $label) {
            $optionArray[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $optionArray;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::CUSTOM_TYPE  => __('Custom Url'),
            self::DEFAULT_TYPE => __('Default Magento Url')
        ];
    }
}

