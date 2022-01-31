<?php

namespace App\Core;

use Psr\Log\LoggerInterface;

class GenerateToken
{
    const MESS_ERROR_RANDOM_STR = 'Random string error: %s';
    const LENGTH_CONFIRMATION_TOKEN = 120;
    const ALPHABET = '0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN';

    private static $logger;

    private function __construct(LoggerInterface $logger)
    {
        self::$logger = $logger;
    }

    public static function getGenerateConfirmationToken(): string
    {
        try {
            return bin2hex(random_bytes(self::LENGTH_CONFIRMATION_TOKEN));
        } catch (\Exception $e) {
           self::$logger->error(sprintf(self::MESS_ERROR_RANDOM_STR, $e->getMessage()), ['exception' => $e]);
        }

        return substr(
            str_shuffle(
                str_repeat(self::ALPHABET, self::LENGTH_CONFIRMATION_TOKEN)
            ),
            0,
            self::LENGTH_CONFIRMATION_TOKEN
        );
    }

}
