<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class EmailMx implements ArrayInterface
{
    public const DEFAULT_TYPE  = 'all';
    public const GMAIL_TYPE    = 'gmail';
    public const GMAIL_MX_TYPE = 'gmailmx';

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
            self::DEFAULT_TYPE  => __('Add to all emails'),
            self::GMAIL_TYPE => __('Add only for @gmail.com'),
            self::GMAIL_MX_TYPE => __('Add only for @gmail.com and emails with Google Workspace MX records')
        ];
    }
}

