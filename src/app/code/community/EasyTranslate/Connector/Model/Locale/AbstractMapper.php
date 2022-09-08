<?php

declare(strict_types=1);

abstract class EasyTranslate_Connector_Model_Locale_AbstractMapper
{
    protected const INTERNAL_TO_EXTERNAL = [];

    public function isMagentoCodeSupported(string $magentoCode): bool
    {
        // extract it into a variable first for PHP 5.6 support
        $mapping = self::INTERNAL_TO_EXTERNAL;

        return isset($mapping[$magentoCode]);
    }

    public function isExternalCodeSupported(string $externalCode): bool
    {
        $mapping = array_flip(static::INTERNAL_TO_EXTERNAL);

        return isset($mapping[$externalCode]);
    }

    public function mapExternalCodeToMagentoCode(string $externalCode): string
    {
        $mapping = array_flip(static::INTERNAL_TO_EXTERNAL);
        if (!$this->isExternalCodeSupported($externalCode)) {
            Mage::throwException('Unsupported locale code.');
        }

        return $mapping[$externalCode];
    }

    public function mapMagentoCodeToExternalCode(string $magentoCode): string
    {
        if (!$this->isMagentoCodeSupported($magentoCode)) {
            Mage::throwException('Unsupported locale code.');
        }

        return static::INTERNAL_TO_EXTERNAL[$magentoCode];
    }
}
