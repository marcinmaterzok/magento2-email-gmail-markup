<?php
/**
 * Copyright © Marcin Materzok - MTRZK Sp. z o .o. (MIT License)
 * See LICENSE_MTRZK for license details.
 */

declare(strict_types=1);

namespace Mtrzk\GmailMarkup\Model;

use Mtrzk\GmailMarkup\Model\Config\Source\EmailMx;

class EmailLookup
{
    public const GMAIL_MX_SERVERS = [
        'aspmx.l.google.com',
        'alt1.aspmx.l.google.com',
        'alt2.aspmx.l.google.com',
        'alt3.aspmx.l.google.com',
        'alt4.aspmx.l.google.com',
        'alt2.aspmx.l.google.com'
    ];

    private const GMAIL_SURFIX = '@gmail.com';

    private Config $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param string $email
     * @param int    $storeId
     *
     * @return bool
     */
    public function checkEmail(string $email, int $storeId): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $markupSettings = $this->config->getAddMarkupEmailSettings($storeId);

        if ($markupSettings === EmailMx::GMAIL_TYPE) {
            return $this->checkGmailAccount($email);
        }

        if ($markupSettings === EmailMx::GMAIL_MX_TYPE) {
            return $this->checkMxRecords($email);
        }

        return true;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    private function checkMxRecords(string $email): bool
    {
        if ($this->checkGmailAccount($email)) {
            return true;
        }

        getmxrr(substr($email, strrpos($email, '@') + 1), $hosts);

        if (!$hosts) {
            return false;
        }

        return !empty(array_intersect(self::GMAIL_MX_SERVERS, $hosts));
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    private function checkGmailAccount(string $email): bool
    {
        return stripos($email, self::GMAIL_SURFIX) !== false;
    }
}
