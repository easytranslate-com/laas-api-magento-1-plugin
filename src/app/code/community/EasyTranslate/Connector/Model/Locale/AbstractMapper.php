<?php

abstract class EasyTranslate_Connector_Model_Locale_AbstractMapper
{
    const INTERNAL_TO_EXTERNAL = [];

    /**
     * @param string $magentoCode
     * @return bool
     */
    public function isMagentoCodeSupported($magentoCode)
    {
        $magentoCode = (string) $magentoCode;
        // extract it into a variable first for PHP 5.6 support
        $mapping = static::INTERNAL_TO_EXTERNAL;

        return isset($mapping[$magentoCode]);
    }

    /**
     * @param string $externalCode
     * @return bool
     */
    public function isExternalCodeSupported($externalCode)
    {
        $externalCode = (string) $externalCode;
        $mapping = array_flip(static::INTERNAL_TO_EXTERNAL);

        return isset($mapping[$externalCode]);
    }

    /**
     * @param string $externalCode
     * @return string
     */
    public function mapExternalCodeToMagentoCode($externalCode)
    {
        $externalCode = (string) $externalCode;
        $mapping = array_flip(static::INTERNAL_TO_EXTERNAL);
        if (!$this->isExternalCodeSupported($externalCode)) {
            Mage::throwException('Unsupported locale code.');
        }

        return $mapping[$externalCode];
    }

    /**
     * @param string $magentoCode
     * @return string
     */
    public function mapMagentoCodeToExternalCode($magentoCode)
    {
        $magentoCode = (string) $magentoCode;
        if (!$this->isMagentoCodeSupported($magentoCode)) {
            Mage::throwException('Unsupported locale code.');
        }

        return static::INTERNAL_TO_EXTERNAL[$magentoCode];
    }
}
