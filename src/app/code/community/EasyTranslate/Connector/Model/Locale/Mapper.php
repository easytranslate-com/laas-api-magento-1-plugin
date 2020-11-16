<?php

declare(strict_types=1);

class EasyTranslate_Connector_Model_Locale_Mapper
{
    // TODO update mapping
    protected const INTERNAL_TO_EXTERNAL
        = [
            'da_DK' => 'da',
            'de_DE' => 'da',
            'en_US' => 'en',
            'en_UK' => 'en',
            'fr_FR' => 'da',
        ];

    public function mapExternalCodeToMagentoCode(string $externalCode): string
    {
        $mapping = array_flip(self::INTERNAL_TO_EXTERNAL);
        if (!isset($mapping[$externalCode])) {
            Mage::throwException('Unsupported locale code.');
        }

        return $mapping[$externalCode];
    }

    public function mapMagentoCodeToExternalCode(string $magentoCode): string
    {
        if (!isset(self::INTERNAL_TO_EXTERNAL[$magentoCode])) {
            Mage::throwException('Unsupported locale code.');
        }

        return self::INTERNAL_TO_EXTERNAL[$magentoCode];
    }
}
